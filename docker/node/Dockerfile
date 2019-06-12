FROM node:10.16

# Update npm to latest version
RUN npm install -g npm

# Set PROJECT USER
RUN groupmod -g 999 node && usermod -u 999 -g 999 node
RUN useradd -ms /bin/bash project
USER project
WORKDIR /project

CMD tail -f /dev/null
