/**
 * MEDINEXT SOLUTIONS  Footer Aurora WebGL Animation
 * Simplex-noise aurora background rendered on #footer-aurora-canvas
 * Brand colors: dark purple  mid purple  primary  coral  orange
 */

(function () {
  'use strict';

  var vsSource = `
    attribute vec2 aVertexPosition;
    varying vec2 vUv;
    void main() {
      vUv = aVertexPosition * 0.5 + 0.5;
      gl_Position = vec4(aVertexPosition, 0.0, 1.0);
    }
  `;

  /* Simplex noise + aurora shader */
  var fsSource = `
    precision mediump float;
    uniform float time;
    uniform vec2  resolution;
    varying vec2  vUv;

    /* --- 2-D Simplex noise (glsl-noise) --- */
    vec3 mod289(vec3 x){ return x - floor(x*(1.0/289.0))*289.0; }
    vec2 mod289(vec2 x){ return x - floor(x*(1.0/289.0))*289.0; }
    vec3 permute(vec3 x){ return mod289(((x*34.0)+1.0)*x); }

    float snoise(vec2 v){
      const vec4 C = vec4(0.211324865405187,
                          0.366025403784439,
                         -0.577350269189626,
                          0.024390243902439);
      vec2 i  = floor(v + dot(v, C.yy));
      vec2 x0 = v -   i + dot(i, C.xx);
      vec2 i1  = (x0.x > x0.y) ? vec2(1.0,0.0) : vec2(0.0,1.0);
      vec4 x12 = x0.xyxy + C.xxzz;
      x12.xy  -= i1;
      i = mod289(i);
      vec3 p = permute(permute(i.y + vec3(0.0,i1.y,1.0))
                              + i.x + vec3(0.0,i1.x,1.0));
      vec3 m = max(0.5 - vec3(dot(x0,x0),
                               dot(x12.xy,x12.xy),
                               dot(x12.zw,x12.zw)), 0.0);
      m = m*m; m = m*m;
      vec3 x  = 2.0*fract(p * C.www) - 1.0;
      vec3 h  = abs(x) - 0.5;
      vec3 ox = floor(x + 0.5);
      vec3 a0 = x - ox;
      m *= 1.79284291400159 - 0.85373472095314*(a0*a0+h*h);
      vec3 g;
      g.x  = a0.x  *x0.x   + h.x  *x0.y;
      g.yz = a0.yz *x12.xz + h.yz *x12.yw;
      return 130.0 * dot(m, g);
    }
    /* -------------------------------------- */

    void main(){
      vec2 uv = vUv;

      float flow1 = snoise(vec2(uv.x * 2.0 + time * 0.1,  uv.y * 0.5 + time * 0.05));
      float flow2 = snoise(vec2(uv.x * 1.5 + time * 0.08, uv.y * 0.8 + time * 0.03));
      float flow3 = snoise(vec2(uv.x * 3.0 + time * 0.12, uv.y * 0.3 + time * 0.07));

      float streaks = sin((uv.x + flow1 * 0.3) * 8.0 + time * 0.2) * 0.5 + 0.5;
      streaks *= sin((uv.y + flow2 * 0.2) * 12.0 + time * 0.15) * 0.5 + 0.5;

      float aurora = (flow1 + flow2 + flow3) * 0.33 + 0.5;
      aurora = pow(aurora, 2.0);

      /* Brand colors (Medinext Blue/Navy Palette) */
      vec3 darkBase   = vec3(0.04, 0.15, 0.28); /* Dark Navy #0A2647 */
      vec3 midPurple  = vec3(0.08, 0.26, 0.45); /* Medium Navy #144272 */
      vec3 primary    = vec3(0.17, 0.45, 0.70); /* Primary Blue #2C74B3 */
      vec3 coral      = vec3(0.13, 0.32, 0.58); /* Light Blue #205295 */
      vec3 orange     = vec3(0.00, 0.66, 0.80); /* Accent Teal #00A8CC */

      vec3 color = darkBase;

      float purpleFlow = smoothstep(0.3, 0.7, aurora + streaks * 0.3);
      color = mix(color, midPurple, purpleFlow);

      float brightFlow = smoothstep(0.6, 0.9, aurora + flow1 * 0.4);
      color = mix(color, primary, brightFlow);

      float coralFlow = smoothstep(0.8, 1.0, streaks + aurora * 0.5);
      color = mix(color, coral, coralFlow * 0.65);

      float orangeFlow = smoothstep(0.7, 0.95, flow3 + streaks * 0.2);
      color = mix(color, orange, orangeFlow * 0.45);

      float noise = snoise(uv * 100.0) * 0.02;
      color += noise;

      gl_FragColor = vec4(color, 1.0);
    }
  `;

  function initAurora(canvas) {
    const gl = canvas.getContext('webgl') || canvas.getContext('experimental-webgl');
    if (!gl) return;

    function compileShader(type, src) {
      const shader = gl.createShader(type);
      gl.shaderSource(shader, src);
      gl.compileShader(shader);
      return shader;
    }

    const vs = compileShader(gl.VERTEX_SHADER, vsSource);
    const fs = compileShader(gl.FRAGMENT_SHADER, fsSource);
    const prog = gl.createProgram();
    gl.attachShader(prog, vs);
    gl.attachShader(prog, fs);
    gl.linkProgram(prog);
    gl.useProgram(prog);

    // Fullscreen quad (2 triangles)
    const buf = gl.createBuffer();
    gl.bindBuffer(gl.ARRAY_BUFFER, buf);
    gl.bufferData(gl.ARRAY_BUFFER, new Float32Array([
      -1, -1, 1, -1, -1, 1,
      -1, 1, 1, -1, 1, 1
    ]), gl.STATIC_DRAW);

    const posLoc = gl.getAttribLocation(prog, 'aVertexPosition');
    gl.enableVertexAttribArray(posLoc);
    gl.vertexAttribPointer(posLoc, 2, gl.FLOAT, false, 0, 0);

    const timeLoc = gl.getUniformLocation(prog, 'time');
    const resLoc = gl.getUniformLocation(prog, 'resolution');

    function resize() {
      canvas.width = canvas.offsetWidth;
      canvas.height = canvas.offsetHeight;
      gl.viewport(0, 0, canvas.width, canvas.height);
      gl.uniform2f(resLoc, canvas.width, canvas.height);
    }
    resize();
    window.addEventListener('resize', resize);

    let t = 0;
    let raf;
    function render() {
      t += 0.01;
      gl.uniform1f(timeLoc, t);
      gl.drawArrays(gl.TRIANGLES, 0, 6);
      raf = requestAnimationFrame(render);
    }

    // Only run when footer is visible (pause off-screen to save GPU)
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(e => {
        if (e.isIntersecting) { if (!raf) render(); }
        else { cancelAnimationFrame(raf); raf = null; }
      });
    }, { threshold: 0.01 });
    observer.observe(canvas);
  }

  document.addEventListener('DOMContentLoaded', function () {
    const canvas = document.getElementById('footer-aurora-canvas');
    if (canvas) initAurora(canvas);
  });
})();
