# Guía de Despliegue en Render

## 📋 Resumen de Cambios Realizados

### Archivos Modificados:
1. ✅ `config/database.php` - Cambiado default de sqlite a mysql
2. ✅ `config/session.php` - SESSION_SECURE_COOKIE cambiado a true
3. ✅ `.env.example` - Añadidas configuraciones de sesión y trusted proxies
4. ✅ `resources/views/auth/login.blade.php` - Eliminada redundancia CSRF
5. ✅ `database/migrations/2025_11_19_000003_add_fields_to_users_table.php` - Cambiado enum a string para compatibilidad con PostgreSQL
6. ✅ `app/Models/User.php` - Añadida validación de rol en el modelo

### Archivos Creados:
1. ✅ `render.yaml` - Configuración completa para Render
2. ✅ `build.sh` - Script de build personalizado
3. ✅ `DEPLOY_RENDER.md` - Este documento

---

## 🚀 Pasos para Desplegar en Render

### 1. Preparación del Repositorio

```bash
# 1. Asegurarse de que build.sh tenga permisos de ejecución
git add build.sh
git update-index --chmod=+x build.sh

# 2. Commit de todos los cambios
git add .
git commit -m "Preparado para despliegue en Render"
```

### 2. Configuración en Render

#### Opción A: Usar render.yaml (Recomendado)

1. Conecta tu repositorio de GitHub/GitLab a Render
2. Render detectará automáticamente el archivo `render.yaml`
3. Verifica que las configuraciones sean correctas
4. Haz clic en "Deploy"

#### Opción B: Configuración Manual

Si prefieres configurar manualmente:

**Web Service:**
- **Name**: aplicacion-extraescolares
- **Environment**: PHP
- **Build Command**: `./build.sh`
- **Start Command**: `php artisan serve --host=0.0.0.0 --port=$PORT`

**Variables de Entorno:**
```
APP_ENV=production
APP_DEBUG=false
APP_KEY=<generado automáticamente>
APP_URL=https://tu-app.onrender.com
LOG_CHANNEL=errorlog
LOG_LEVEL=warning
DB_CONNECTION=pgsql
DB_HOST=<del servicio de base de datos>
DB_PORT=5432
DB_DATABASE=aplicacion_integral
DB_USERNAME=<del servicio de base de datos>
DB_PASSWORD=<del servicio de base de datos>
CACHE_DRIVER=database
SESSION_DRIVER=database
QUEUE_CONNECTION=database
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=lax
TRUSTED_PROXIES=*
FILESYSTEM_DISK=local
```

**PostgreSQL Database:**
- **Name**: aplicacion-db
- **Database**: aplicacion_integral
- **User**: generado automáticamente
- **Password**: generado automáticamente

---

## 🔧 Configuración Adicional para PostgreSQL

Como Render usa PostgreSQL en su plan gratuito, necesitas asegurarte de que tus migraciones sean compatibles.

### Verificar migraciones:

Las migraciones existentes usan `Schema::create()` que es compatible con PostgreSQL. Sin embargo, verifica:

1. No uses tipos específicos de MySQL como `enum` si los tienes
2. Los timestamps deben usar el formato estándar
3. Las longitudes de strings deben ser razonables

### Si encuentras problemas con tipos específicos:

Revisa las migraciones en `database/migrations/` y asegúrate de que:
- No uses `enum` - usa `string` con validación en el modelo
- Los índices únicos estén correctamente definidos
- Las claves foráneas usen nombres estándar

---

## 📦 Variables de Entorno Completas

### Variables de Aplicación:
```bash
APP_NAME="Aplicación Extraescolares"
APP_ENV=production
APP_DEBUG=false
APP_KEY=<generado por Render>
APP_URL=https://tu-app.onrender.com
APP_LOCALE=es
APP_FALLBACK_LOCALE=es
APP_FAKER_LOCALE=es_MX
```

### Base de Datos (PostgreSQL en Render):
```bash
DB_CONNECTION=pgsql
DB_HOST=<automático>
DB_PORT=5432
DB_DATABASE=aplicacion_integral
DB_USERNAME=<automático>
DB_PASSWORD=<automático>
```

### Sesión y Seguridad:
```bash
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=lax
TRUSTED_PROXIES=*
```

### Cache y Colas:
```bash
CACHE_DRIVER=database
QUEUE_CONNECTION=database
```

### Storage:
```bash
FILESYSTEM_DISK=local
```

### Logging:
```bash
LOG_CHANNEL=errorlog
LOG_LEVEL=warning
```

---

## 🐛 Solución de Problemas Comunes

### Error 419 Page Expired

**Causa**: Token CSRF inválido o problemas de sesión

**Solución**:
1. Verificar que `SESSION_SECURE_COOKIE=true` en producción
2. Verificar que `TRUSTED_PROXIES=*` esté configurado
3. Limpiar caché: `php artisan cache:clear`
4. Limpiar sesiones: `php artisan session:clear`

### Error 500 Server Error

**Causa**: Error de conexión a base de datos o configuración

**Solución**:
1. Verificar logs en Render: Dashboard → Logs
2. Verificar que la base de datos esté ejecutándose
3. Verificar que las migraciones se ejecutaron correctamente
4. Verificar permisos de directorios storage y bootstrap/cache

### Problemas de Login

**Causa**: Contraseñas hasheadas incorrectamente

**Solución**:
1. El AuthController ya maneja contraseñas en texto plano y hasheadas
2. Para producción, todas las contraseñas deberían estar hasheadas
3. Si hay usuarios con contraseñas en texto plano, el sistema las rehashearán automáticamente en el primer login

### Timeout en Deploy

**Causa**: Tiempo de build excedido

**Solución**:
1. Usar plan Starter o Professional de Render
2. Optimizar dependencias en composer.json
3. Reducir número de migraciones si es posible

---

## 🔒 Consideraciones de Seguridad

### HTTPS
- Render proporciona HTTPS automáticamente
- `AppServiceProvider.php` ya fuerza HTTPS en producción ✅
- `SESSION_SECURE_COOKIE=true` configurado ✅

### Storage
- El storage local no es persistente en Render
- Para archivos permanentes, considera usar:
  - AWS S3 (recomendado)
  - DigitalOcean Spaces
  - Otro servicio de almacenamiento

### Base de Datos
- Render PostgreSQL tiene cifrado en tránsito
- Las credenciales se gestionan automáticamente
- Considera usar SSL en la conexión (está configurado en config/database.php)

---

## 📊 Monitoreo y Logs

### Ver Logs en Render:
1. Dashboard → aplicacion-extraescolares → Logs
2. Dashboard → aplicacion-db → Logs

### Logs de Laravel:
- `LOG_CHANNEL=errorlog` envía logs a stdout de Render
- Puedes cambiar a `file` si prefieres logs en storage/logs

---

## 🔄 Actualizaciones Futuras

### Para actualizar la aplicación:
1. Haz cambios en tu repositorio
2. Render detectará el push y hará redeploy automático
3. Las migraciones se ejecutarán automáticamente

### Para ejecutar migraciones manuales:
```bash
# En el shell de Render (Dashboard → Shell)
php artisan migrate:fresh --seed
```

---

## 📞 Soporte

Si encuentras problemas:
1. Revisa los logs en Render
2. Verifica que todas las variables de entorno estén configuradas
3. Asegúrate de que la base de datos esté en el mismo datacenter que la app
4. Consulta la documentación de Render: https://render.com/docs

---

## ✅ Checklist de Despliegue

- [ ] Repositorio conectado a Render
- [ ] render.yaml configurado
- [ ] build.sh con permisos de ejecución
- [ ] Base de datos PostgreSQL creada
- [ ] Variables de entorno configuradas
- [ ] Migraciones ejecutadas
- [ ] Storage link creado
- [ ] Assets compilados (Vite)
- [ ] HTTPS funcionando
- [ ] Login funcional
- [ ] CSRF funcionando
- [ ] Logs visibles

---

## 🎯 Resumen

Tu aplicación está lista para desplegar en Render con:
- ✅ Configuración de sesiones corregida (evita Error 419)
- ✅ Configuración de base de datos estándar (evita Error 500)
- ✅ Sistema de autenticación compatible
- ✅ Build script optimizado
- ✅ Variables de entorno configuradas
- ✅ HTTPS forzado en producción
- ✅ Seguridad mejorada con trusted proxies

¡Buena suerte con el despliegue! 🚀
