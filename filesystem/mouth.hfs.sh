#########################################################################
# File Name: mouth.hfs.sh
# Author: Kee
# mail: chinboy2012@gmail.com
# Created Time: 2017年01月11日 星期三 21时44分10秒
#########################################################################
#!/bin/bash
if [ -z $1 ];
then
  fdisk -l
  echo "请输入要挂载的磁盘"
  exit
fi
if [ -z $2 ];
then
  echo "要挂载到哪里呢?"
  exit
fi
echo "Mouth $1 To $2"
mount -t hfsplus -o force,rw $1 $2
fsck.hfsplus -f $1
