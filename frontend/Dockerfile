FROM node:20-alpine

WORKDIR /app

RUN npm install -g @quasar/cli

COPY package.json package-lock.json* ./

RUN npm install

COPY . .

EXPOSE 9000

CMD ["npx", "quasar", "dev", "--port", "9000", "--hostname", "0.0.0.0"]
