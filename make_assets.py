from PIL import Image,ImageDraw,ImageFont,ImageFilter
import os,random,math
random.seed(12)
out='public/images/sumatra';os.makedirs(out,exist_ok=True)
reg='/usr/share/fonts/truetype/dejavu/DejaVuSans.ttf';bold='/usr/share/fonts/truetype/dejavu/DejaVuSans-Bold.ttf'
for num,(code,form,size) in enumerate([('C25','cube','25 MM'),('C26','cube','26 MM'),('C27','cube','27 MM'),('C28','cube','28 MM'),('HEXA','hexa','HEXAGON'),('PILLOW','pillow','PILLOW')]):
 W,H=900,765; im=Image.new('RGB',(W,H),(234+num%2*2,231+num%3*2,225));d=ImageDraw.Draw(im)
 # warm studio ground and backdrop
 for y in range(H):
  k=y/H;d.line([(0,y),(W,y)],fill=(int(241-23*k),int(238-25*k),int(231-24*k)))
 d.ellipse((145,630,785,724), fill=(194,185,173));d.ellipse((180,655,750,713),fill=(174,163,153))
 # sophisticated 3D box
 x=300+(num%2)*12; y=108;bw=340;bh=458
 d.polygon([(x+bw,y+19),(x+bw+96,y+2),(x+bw+96,y+bh-29),(x+bw,y+bh)],fill=(29,29,28))
 d.polygon([(x,y),(x+65,y-49),(x+bw+96,y+2),(x+bw,y+19)],fill=(62,57,51))
 d.rectangle((x,y,x+bw,y+bh),fill=(28,29,27))
 d.rectangle((x+13,y+13,x+bw-13,y+bh-14),outline=(76,63,51),width=2)
 # leaf line decoration
 for k in range(9):
  t=k/9; d.arc((x+bw-125+t*20,y-22+t*26,x+bw+75+t*13,y+260+t*35),60,110,fill=(126,83,50),width=2)
 d.text((x+32,y+50),'SUMATRA',font=ImageFont.truetype(bold,39),fill=(195,143,95))
 d.text((x+32,y+96),'BRIKS',font=ImageFont.truetype(bold,49),fill=(198,151,109))
 d.line([(x+33,y+170),(x+95,y+170)],fill=(188,134,91),width=3)
 d.text((x+32,y+192),'COCONUT SHELL',font=ImageFont.truetype(reg,19),fill=(207,198,181))
 d.text((x+32,y+220),'CHARCOAL BRIQUETTES',font=ImageFont.truetype(reg,15),fill=(207,198,181))
 d.text((x+32,y+292),code,font=ImageFont.truetype(bold,88 if len(code)<5 else 52),fill=(187,132,90))
 d.text((x+35,y+399),size+'  /  CONCEPT PACK',font=ImageFont.truetype(bold,13),fill=(198,185,164))
 # charcoal pieces in front of box
 for i in range(4):
  cx=155+i*138;cy=575+(i%2)*32; r=57+(num if form=='cube' else 0)*2
  if form=='cube':
   d.polygon([(cx-r,cy-35),(cx-r+32,cy-57),(cx+r+22,cy-55),(cx+r,cy-35)],fill=(55,56,55))
   d.polygon([(cx-r,cy-35),(cx+r,cy-35),(cx+r,cy+65),(cx-r,cy+52)],fill=(31,32,31))
   d.polygon([(cx+r,cy-35),(cx+r+22,cy-55),(cx+r+22,cy+42),(cx+r,cy+65)],fill=(19,20,20))
  elif form=='hexa':
   pts=[(cx+math.cos(a*math.pi/3)*55,cy+math.sin(a*math.pi/3)*53) for a in range(6)]
   d.polygon(pts,fill=(31,32,31),outline=(68,67,62),width=3)
   d.ellipse((cx-15,cy-15,cx+15,cy+15),fill=(15,15,15))
  else:
   d.rounded_rectangle((cx-69,cy-37,cx+68,cy+61),radius=34,fill=(29,30,29),outline=(55,54,52),width=3)
 # flecks
 for _ in range(250):
  px=random.randint(85,770);py=random.randint(536,670)
  if im.getpixel((px,py))[0]<80:
   col=random.choice([(53,52,50),(44,44,42),(61,58,55)])
   d.point((px,py),fill=col)
 im.save(os.path.join(out,code.lower()+'.png'),optimize=True)
print('Created six product assets')
