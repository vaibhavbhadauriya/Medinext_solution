/**
 * Color Panels WebGL Shader Background
 * Adapted from Paper Shaders (Apache-2.0) for Medinext CTA Section
 */
(function () {
  'use strict';

  var canvas = document.getElementById('cta-color-panels-canvas');
  if (!canvas) return;

  var gl = canvas.getContext('webgl', { antialias: false, powerPreference: 'low-power' });
  if (!gl) return;

  var VERT = [
    'attribute vec2 a_position;',
    'void main() {',
    '  gl_Position = vec4(a_position, 0.0, 1.0);',
    '}'
  ].join('\n');

  var FRAG = [
    '#ifdef GL_FRAGMENT_PRECISION_HIGH',
    'precision highp float;',
    '#else',
    'precision mediump float;',
    '#endif',
    '',
    'uniform vec3 u_colors[8];',
    'uniform vec4 u_scene;',
    'uniform vec4 u_shape;',
    'uniform vec4 u_surface;',
    'uniform vec4 u_finish;',
    'uniform vec4 u_transform;',
    'uniform vec4 u_space;',
    'uniform vec4 u_cursor;',
    '',
    '#define u_resolution u_scene.xy',
    '#define u_time u_scene.z',
    '#define u_colorCount u_scene.w',
    '#define u_scale u_shape.x',
    '#define u_intensity u_shape.y',
    '#define u_paramA u_shape.z',
    '#define u_warp u_shape.w',
    '#define u_detail u_surface.x',
    '#define u_contrast u_surface.y',
    '#define u_brightness u_surface.z',
    '#define u_saturation u_surface.w',
    '#define u_hue u_finish.x',
    '#define u_vignette u_finish.y',
    '#define u_blur u_finish.z',
    '#define u_grain u_finish.w',
    '#ifdef GL_FRAGMENT_PRECISION_HIGH',
    '#define u_seed u_transform.x',
    '#else',
    '#define u_seed mod(u_transform.x, 31.0)',
    '#endif',
    '#define u_rotate u_transform.y',
    '#define u_drift u_transform.z',
    '#define u_oklab u_transform.w',
    '#define u_offset u_space.xy',
    '#define u_mouse u_space.zw',
    '#define u_cursorPresence u_cursor.x',
    '#define u_cursorEffect u_cursor.y',
    '#define u_cursorStrength u_cursor.z',
    '#define u_cursorRadius u_cursor.w',
    '',
    'float hash21(vec2 p) {',
    '#ifndef GL_FRAGMENT_PRECISION_HIGH',
    '  p = mod(p, 31.0);',
    '#endif',
    '  p = fract(p * vec2(234.34, 435.345));',
    '  p += dot(p, p + 34.23);',
    '  return fract(p.x * p.y);',
    '}',
    '',
    'float grainHash(vec2 p) {',
    '  vec3 p3 = fract(vec3(p.xyx) * 0.1031);',
    '  p3 += dot(p3, p3.yzx + 33.33);',
    '  return fract((p3.x + p3.y) * p3.z);',
    '}',
    '',
    'float noise(vec2 p) {',
    '  vec2 i = floor(p);',
    '  vec2 f = fract(p);',
    '  vec2 u = f * f * (3.0 - 2.0 * f);',
    '  return mix(',
    '    mix(hash21(i), hash21(i + vec2(1.0, 0.0)), u.x),',
    '    mix(hash21(i + vec2(0.0, 1.0)), hash21(i + vec2(1.0, 1.0)), u.x),',
    '    u.y);',
    '}',
    '',
    'float fbm(vec2 p) {',
    '  float v = 0.0;',
    '  float a = 0.5;',
    '  for (int i = 0; i < 5; i++) {',
    '    v += a * noise(p);',
    '    p = p * 2.03 + vec2(17.0, 9.2);',
    '    a *= 0.5;',
    '  }',
    '  return v;',
    '}',
    '',
    'vec3 srgbToLinear(vec3 c) {',
    '  return mix(c / 12.92, pow((c + 0.055) / 1.055, vec3(2.4)), step(0.04045, c));',
    '}',
    'vec3 linearToSrgb(vec3 c) {',
    '  return mix(c * 12.92, 1.055 * pow(max(c, vec3(0.0)), vec3(1.0 / 2.4)) - 0.055, step(0.0031308, c));',
    '}',
    'vec3 linToOklab(vec3 c) {',
    '  float l = 0.4122214708 * c.r + 0.5363325363 * c.g + 0.0514459929 * c.b;',
    '  float m = 0.2119034982 * c.r + 0.6806995451 * c.g + 0.1073969566 * c.b;',
    '  float s = 0.0883024619 * c.r + 0.2817188376 * c.g + 0.6299787005 * c.b;',
    '  l = pow(max(l, 0.0), 1.0 / 3.0);',
    '  m = pow(max(m, 0.0), 1.0 / 3.0);',
    '  s = pow(max(s, 0.0), 1.0 / 3.0);',
    '  return vec3(',
    '    0.2104542553 * l + 0.7936177850 * m - 0.0040720468 * s,',
    '    1.9779984951 * l - 2.4285922050 * m + 0.4505937099 * s,',
    '    0.0259040371 * l + 0.7827717662 * m - 0.8086757660 * s);',
    '}',
    'vec3 oklabToLin(vec3 c) {',
    '  float l = c.x + 0.3963377774 * c.y + 0.2158037573 * c.z;',
    '  float m = c.x - 0.1055613458 * c.y - 0.0638541728 * c.z;',
    '  float s = c.x - 0.0894841775 * c.y - 1.2914855480 * c.z;',
    '  l = l * l * l; m = m * m * m; s = s * s * s;',
    '  return vec3(',
    '    4.0767416621 * l - 3.3077115913 * m + 0.2309699292 * s,',
    '    -1.2684380046 * l + 2.6097574011 * m - 0.3413193965 * s,',
    '    -0.0041960863 * l - 0.7034186147 * m + 1.7076147010 * s);',
    '}',
    'vec3 mixColour(vec3 a, vec3 b, float t) {',
    '  if (u_oklab > 0.5) {',
    '    vec3 la = linToOklab(srgbToLinear(a));',
    '    vec3 lb = linToOklab(srgbToLinear(b));',
    '    return clamp(linearToSrgb(oklabToLin(mix(la, lb, t))), 0.0, 1.0);',
    '  }',
    '  return mix(a, b, t);',
    '}',
    '',
    'vec3 palette(float x) {',
    '  float n = max(u_colorCount - 1.0, 1.0);',
    '  float f = clamp(x, 0.0, 1.0) * n;',
    '  vec3 col = u_colors[0];',
    '  for (int i = 0; i < 7; i++) {',
    '    if (float(i) < n)',
    '      col = mixColour(col, u_colors[i + 1], smoothstep(0.0, 1.0, clamp(f - float(i), 0.0, 1.0)));',
    '  }',
    '  return col;',
    '}',
    '',
    'vec3 shade(vec2 uv, vec2 p, float t) {',
    '  float perspective = 0.2 + u_intensity * 0.75;',
    '  float count = 3.0 + floor(u_paramA * 5.0);',
    '  float z = p.y + sin(p.x * 1.4 + t * 0.25) * perspective * 0.22;',
    '  float phase = p.x * count + z * perspective * 2.0 + t * 0.22;',
    '  float cell = fract(phase) - 0.5;',
    '  float panel = 1.0 - smoothstep(0.34, 0.49, abs(cell));',
    '  float bevel = 1.0 - smoothstep(0.28, 0.49, abs(cell));',
    '  float depth = 0.5 + 0.5 * cos((floor(phase) + z * 0.8) * 1.7);',
    '  float sheen = pow(max(0.0, 1.0 - abs(cell + sin(t * 0.2) * 0.25) * 2.0), 7.0);',
    '  vec3 colour = palette(clamp(depth + sheen * 0.45, 0.0, 1.0));',
    '  return mix(u_colors[0] * 0.35, colour * (0.65 + bevel * 0.5), panel);',
    '}',
    '',
    'void main() {',
    '  vec2 uv = gl_FragCoord.xy / u_resolution.xy;',
    '  vec2 p = (gl_FragCoord.xy - 0.5 * u_resolution.xy) / min(u_resolution.x, u_resolution.y);',
    '  uv = p * min(u_resolution.x, u_resolution.y) / u_resolution.xy + 0.5;',
    '  p *= u_scale;',
    '  p += u_offset;',
    '  vec3 col = shade(uv, p, u_time);',
    '  if (abs(u_contrast - 1.0) > 0.0001) col = (col - 0.5) * u_contrast + 0.5;',
    '  if (u_grain > 0.0001) col += (grainHash(gl_FragCoord.xy + vec2(u_seed * 17.0, u_seed * 31.0)) - 0.5) * u_grain;',
    '  gl_FragColor = vec4(clamp(col, 0.0, 1.0), 1.0);',
    '}'
  ].join('\n');

  var UNIFORMS = {
    colors: [
      [0.02745, 0.0196, 0.05098],
      [0.16078, 0.07058, 0.34117],
      [0.65882, 0.23529, 0.65882],
      [1.0, 0.6196, 0.39215],
      [1.0, 0.6196, 0.39215],
      [1.0, 0.6196, 0.39215],
      [1.0, 0.6196, 0.39215],
      [1.0, 0.6196, 0.39215]
    ],
    colorCount: 4,
    scale: 1.260,
    intensity: 0.350,
    paramA: 0.280,
    warp: 0.000,
    detail: 1.824,
    contrast: 1.005,
    brightness: 0.000,
    saturation: 1.000,
    hue: 0.0000,
    vignette: 0.000,
    blur: 0.0000,
    grain: 0.042,
    seed: 1.0,
    rotate: 0.0000,
    offsetX: 0.000,
    offsetY: 0.000,
    drift: 0.000,
    cursorEnabled: false,
    cursorEffect: 2.0,
    cursorStrength: 0.650,
    cursorRadius: 0.460,
    oklab: 0.0,
    timeScale: 0.575
  };

  function compile(type, src) {
    var s = gl.createShader(type);
    gl.shaderSource(s, src);
    gl.compileShader(s);
    return s;
  }

  var program = gl.createProgram();
  var vertexShader = compile(gl.VERTEX_SHADER, VERT);
  var fragmentShader = compile(gl.FRAGMENT_SHADER, FRAG);
  gl.attachShader(program, vertexShader);
  gl.attachShader(program, fragmentShader);
  gl.linkProgram(program);
  gl.deleteShader(vertexShader);
  gl.deleteShader(fragmentShader);
  gl.useProgram(program);

  var buf = gl.createBuffer();
  gl.bindBuffer(gl.ARRAY_BUFFER, buf);
  gl.bufferData(gl.ARRAY_BUFFER, new Float32Array([-1, -1, 3, -1, -1, 3]), gl.STATIC_DRAW);
  var loc = gl.getAttribLocation(program, 'a_position');
  gl.enableVertexAttribArray(loc);
  gl.vertexAttribPointer(loc, 2, gl.FLOAT, false, 0, 0);

  var flatColors = [];
  for (var i = 0; i < UNIFORMS.colors.length; i++) {
    flatColors.push(UNIFORMS.colors[i][0], UNIFORMS.colors[i][1], UNIFORMS.colors[i][2]);
  }

  var uni = {
    colors: gl.getUniformLocation(program, 'u_colors'),
    scene: gl.getUniformLocation(program, 'u_scene'),
    shape: gl.getUniformLocation(program, 'u_shape'),
    surface: gl.getUniformLocation(program, 'u_surface'),
    finish: gl.getUniformLocation(program, 'u_finish'),
    transform: gl.getUniformLocation(program, 'u_transform'),
    space: gl.getUniformLocation(program, 'u_space'),
    cursor: gl.getUniformLocation(program, 'u_cursor')
  };

  gl.uniform3fv(uni.colors, new Float32Array(flatColors));
  gl.uniform4f(uni.shape, UNIFORMS.scale, UNIFORMS.intensity, UNIFORMS.paramA, UNIFORMS.warp);
  gl.uniform4f(uni.surface, UNIFORMS.detail, UNIFORMS.contrast, UNIFORMS.brightness, UNIFORMS.saturation);
  gl.uniform4f(uni.finish, UNIFORMS.hue, UNIFORMS.vignette, UNIFORMS.blur, UNIFORMS.grain);
  gl.uniform4f(uni.transform, UNIFORMS.seed, UNIFORMS.rotate, UNIFORMS.drift, UNIFORMS.oklab);
  gl.uniform4f(uni.cursor, 0, UNIFORMS.cursorEffect, UNIFORMS.cursorStrength, UNIFORMS.cursorRadius);

  var raf = 0;
  var visible = document.visibilityState === 'visible';
  var inView = false;
  var start = performance.now();

  function resizeCanvas() {
    var rect = canvas.getBoundingClientRect();
    var dpr = Math.min(window.devicePixelRatio || 1, 1.5);
    var w = Math.max(1, Math.round(rect.width * dpr));
    var h = Math.max(1, Math.round(rect.height * dpr));
    if (canvas.width !== w || canvas.height !== h) {
      canvas.width = w;
      canvas.height = h;
      gl.viewport(0, 0, w, h);
    }
  }

  function render(now) {
    raf = 0;
    if (!visible || !inView) return;

    resizeCanvas();
    var w = canvas.width;
    var h = canvas.height;

    gl.uniform4f(uni.scene, w, h, ((now - start) / 1000) * UNIFORMS.timeScale, UNIFORMS.colorCount);
    gl.uniform4f(uni.space, UNIFORMS.offsetX, UNIFORMS.offsetY, 0, 0);
    gl.drawArrays(gl.TRIANGLES, 0, 3);

    raf = requestAnimationFrame(render);
  }

  function requestRender() {
    if (visible && inView && raf === 0) {
      raf = requestAnimationFrame(render);
    }
  }

  // IntersectionObserver: Only runs when CTA section is visible on screen!
  var observer = new IntersectionObserver(function (entries) {
    inView = entries[0] && entries[0].isIntersecting;
    if (inView) {
      requestRender();
    } else if (raf !== 0) {
      cancelAnimationFrame(raf);
      raf = 0;
    }
  }, { threshold: 0.05 });
  observer.observe(canvas);

  document.addEventListener('visibilitychange', function () {
    visible = document.visibilityState === 'visible';
    if (visible && inView) {
      requestRender();
    } else if (raf !== 0) {
      cancelAnimationFrame(raf);
      raf = 0;
    }
  });

  window.addEventListener('resize', function () {
    if (inView) {
      resizeCanvas();
      requestRender();
    }
  }, { passive: true });

})();
