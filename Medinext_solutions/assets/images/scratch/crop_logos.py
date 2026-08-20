import cv2
import numpy as np
import os

img_path = '../ehr.webp'
img = cv2.imread(img_path)
if img is None:
    print("Could not read image")
    exit(1)

# Convert to grayscale
gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)

banner_cutoff = 130
img_no_banner = img[banner_cutoff:, :]
gray_no_banner = gray[banner_cutoff:, :]

# Thresholding
_, thresh = cv2.threshold(gray_no_banner, 240, 255, cv2.THRESH_BINARY_INV)

# Morphological operations to group text and logo parts together
# Use a wide kernel to connect text horizontally
kernel = cv2.getStructuringElement(cv2.MORPH_RECT, (25, 10))
dilated = cv2.dilate(thresh, kernel, iterations=1)

# Find contours
contours, _ = cv2.findContours(dilated, cv2.RETR_EXTERNAL, cv2.CHAIN_APPROX_SIMPLE)

bounding_boxes = []
for c in contours:
    x, y, w, h = cv2.boundingRect(c)
    if w > 30 and h > 10 and w < 300 and h < 150: # Filter by size
        bounding_boxes.append((x, y, w, h))

# Group by row using a tolerance for y (e.g., 40 pixels)
# We can sort by y first
bounding_boxes.sort(key=lambda b: b[1])

# Assign rows
rows = []
current_row = []
last_y = -100

for b in bounding_boxes:
    if b[1] - last_y > 35: # new row
        if current_row:
            rows.append(sorted(current_row, key=lambda b: b[0]))
        current_row = [b]
        last_y = b[1]
    else:
        current_row.append(b)
if current_row:
    rows.append(sorted(current_row, key=lambda b: b[0]))

os.makedirs('crops', exist_ok=True)
count = 0
for r_idx, row in enumerate(rows):
    for c_idx, (x, y, w, h) in enumerate(row):
        # Add padding
        pad = 10
        x1 = max(0, x - pad)
        y1 = max(0, y - pad)
        x2 = min(img_no_banner.shape[1], x + w + pad)
        y2 = min(img_no_banner.shape[0], y + h + pad)
        
        crop = img_no_banner[y1:y2, x1:x2]
        cv2.imwrite(f'crops/row{r_idx}_col{c_idx}.png', crop)
        count += 1

print(f"Cropped {count} logos into {len(rows)} rows.")
