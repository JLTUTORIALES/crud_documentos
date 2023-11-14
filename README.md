# CRUD DE DOCUMENTOS
Este proyecto consiste en un CRUD a través de una API RESTful desarrollada mediante PHP y consumida por una frontend desarrollado con Vue.js

El demo del proyecto se encuentra disponible en: https://cruddocumentos.luisjdev.com

## Credenciales de acceso

Se definieron los siguientes dos usuarios de acceso bajo las siguentes credenciales:

Usuario 1:
- usuario: jortiz
- contraseña: JOSEluis123

Usuario 2:
- usuario: mmateos
- contraseña: Miguel23*

# Documentación

## Backend

Para el backend se utilizó el lenguaje PHP basándose en la arquitectura de N capas y una báse de datos mysql, se hace uso de Composer para la gestión de las dependencias, la api se encuentra desplegada en: https://api.luisjdev.com/cruce_documentos/

### Dependencias
El proyecto hace uso de las siguientes dependencias:  

- [firebase/php-jwt](https://packagist.org/packages/firebase/php-jwt) Para autenticación mediante JWT.
- [vlucas/phpdotenv](https://packagist.org/packages/vlucas/phpdotenv) Para gestionar las variables de entorno del la implementación.
- [respect/validation](https://packagist.org/packages/respect/validation) Para realizar las validaciondes del payload que trabajarán los endpoints.

### Endpoints

La API se compone de cuatro servicios los cuales corresponden a:
- Servicio de autenticación.
- Serivcio de gestión de procesos de documentos.
- Servicio de gestión de tipos de documentos.
- Servicio de gestión de documentos.

A continuación se ejemplifican cada uno de los endpoints:

#### Servicio de Autenticación

Endpoint:
```HTTP
POST /auth
```
Payload:
```JSON
{
    "user" : "someUser",
    "pwd" : "somePassword"
}
```
Response:

En caso de error:
```JSON
{
    "status": "error",
    "message": "User not found!"
}
```
Login exitoso:
```JSON
{
    "status": "success",
    "data": {
        "id": 1,
        "name": "Jose Luis Ortiz",
        "username": "jortiz",
        "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6MSwibmFtZSI6Ikpvc2UgTHVpcyBPcnRpeiIsInVzZXJuYW1lIjoiam9ydGl6IiwiaWF0IjoxNjk5ODkzNjg3LCJleHAiOjE2OTk4OTcyODd9.FFyyVZIZh17kXkRZzwlH93hSf-SDKZnMfGXZjek4xOs"
    }
}
```
#### Serivcio de gestión de procesos de documentos.
Endpoint:
```HTTP
GET /process
```
Headers:
```HTTP
Authorization: Bearer JWT_TOKEN
```
Response:
```JSON
[
    {
        "id": 1,
        "pro_prefix": "ING",
        "pro_name": "Ingeniería"
    },
    {
        "id": 2,
        "pro_prefix": "ARQ",
        "pro_name": "Arquitectura"
    },
    ...
]
```
#### Serivcio de gestión de tipos de documentos.
Endpoint:
```HTTP
GET /types
```
Headers:
```HTTP
Authorization: Bearer JWT_TOKEN
```
Response:
```json
[
    {
        "id": 1,
        "tip_prefix": "INS",
        "tip_name": "Instructivo"
    },
    {
        "id": 2,
        "tip_prefix": "ART",
        "tip_name": "Artículo"
    },
    ...
]
```
#### Servicio de gestión de documentos.
Headers:
```HTTP
Authorization: Bearer JWT_TOKEN
```

Endpoint:
```HTTP
GET /documents        obtiene todos los documentos creados
POST /documents       crea un documento
PUT /documents        actualiza un documento
DELETE /documents     elimina un documento
```
Payloads:

Post:
```JSON
{
    "name" : "Documento 3",
    "content" : "Este es un documento de prueba con contenido de prueba",
    "id_type" : 4,
    "id_process" : 1
}
```
PUT:
```JSON
{
    "id": 3,
    "name" : "Documento 3",
    "content" : "Este es un documento de prueba con contenido de prueba",
    "id_type" : 4,
    "id_process" : 1
}
```
DELETE:
```JSON
{
    "id": 3
}
```

## Frontend
El frontend se desarrolló haciendo uso de Vue.js 3 + Bootstrap de forma completamente indepeniente del backend, de hecho, se encuentran desplegados en diferentes servidores.

### Dependencias
El proyecto hace uso de las siguientes dependencias:
- [vue-router](https://www.npmjs.com/package/vue-router) Para el manejo de rutas.
- [axios](https://www.npmjs.com/package/axios) Para realizar las peticiones a la API.
- [bootstrap](https://www.npmjs.com/package/bootstrap) Para el diseño de la interfaz.

contacto@luisjdev.com
2023