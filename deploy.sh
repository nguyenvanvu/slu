######SETTING########
SRC_DIR="/localsrc/php53/rinsho/"
DEPLOY_EX_FILE="/localsrc/php53/rinsho/rsync_exclude.txt"
DES_DIR="27.120.83.57:/localsrc/rinsho/"
LOG_FILE="/localsrc/php53/rinsho/deploy_log.txt"

###### Get source ########
cd $SRC_DIR
svn up

##### COPY TO JPS1
rsync -avz  $SRC_DIR --rsh='ssh -p2258 -l root' $DES_DIR --exclude-from $DEPLOY_EX_FILE --force --delete --log-file=$LOG_FILE

