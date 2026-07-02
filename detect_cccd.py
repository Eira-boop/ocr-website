import cv2
import numpy as np
import sys

if len(sys.argv) != 3:
    print("Usage: python detect_cccd.py input.jpg output.jpg")
    exit()

input_path = sys.argv[1]
output_path = sys.argv[2]

img = cv2.imread(input_path)

if img is None:
    print("Không đọc được ảnh")
    exit()

# Phóng to
img = cv2.resize(img, None, fx=2, fy=2)

gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)

# Làm mờ
blur = cv2.GaussianBlur(gray, (5,5), 0)

# Adaptive Threshold
th = cv2.adaptiveThreshold(
    blur,
    255,
    cv2.ADAPTIVE_THRESH_GAUSSIAN_C,
    cv2.THRESH_BINARY_INV,
    31,
    8
)

kernel = cv2.getStructuringElement(cv2.MORPH_RECT,(7,7))
th = cv2.morphologyEx(th, cv2.MORPH_CLOSE, kernel)

contours,_ = cv2.findContours(
    th,
    cv2.RETR_EXTERNAL,
    cv2.CHAIN_APPROX_SIMPLE
)

best=None
bestArea=0

for c in contours:

    area=cv2.contourArea(c)

    if area<30000:
        continue

    rect=cv2.minAreaRect(c)

    w,h=rect[1]

    if w==0 or h==0:
        continue

    ratio=max(w,h)/min(w,h)

    if 1.45<ratio<1.8:

        if area>bestArea:
            bestArea=area
            best=rect

if best is None:
    print("Không tìm thấy CCCD")
    exit()

box=cv2.boxPoints(best)
box=np.int32(box)

width=int(max(best[1]))
height=int(min(best[1]))

dst=np.array([
    [0,0],
    [width-1,0],
    [width-1,height-1],
    [0,height-1]
],dtype="float32")

pts=np.array(box,dtype="float32")

# Sắp xếp 4 điểm
s=pts.sum(axis=1)
diff=np.diff(pts,axis=1)

rect=np.zeros((4,2),dtype="float32")
rect[0]=pts[np.argmin(s)]
rect[2]=pts[np.argmax(s)]
rect[1]=pts[np.argmin(diff)]
rect[3]=pts[np.argmax(diff)]

M=cv2.getPerspectiveTransform(rect,dst)

warp=cv2.warpPerspective(img,M,(width,height))

cv2.imwrite(output_path,warp)

print("Đã cắt CCCD")