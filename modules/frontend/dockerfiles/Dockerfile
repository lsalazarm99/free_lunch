FROM node:16.10.0 as build
#
WORKDIR /app
#
COPY . ./
#
RUN npm install
#
RUN npm run build
#
#-----------------------------------------------------------------------------------------------------------------------
#
FROM nginx:1.21.4 AS production
#
COPY --from=build /app/dist /etc/nginx/html
#
COPY ./dockerfiles/config/nginx/nginx.conf /etc/nginx/conf.d/default.conf
