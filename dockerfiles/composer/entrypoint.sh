#!/bin/sh

# Add local user
# Either use the LOCAL_USER_ID if passed in at runtime or
# fallback

USER_ID=${LOCAL_USER_ID:-9001}

echo "Starting with UID : $USER_ID"
adduser -D -s /bin/sh -u $USER_ID user
chown -R user:user /project /composer
export HOME=/home/user

su-exec user "$@"
