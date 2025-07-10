# Security Guidelines

## Environment Variables

This project uses environment variables to store sensitive configuration data. Follow these guidelines to maintain security:

### Required Environment Variables

Copy `.env.example` to `.env` and configure the following variables:

#### Database Configuration
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bliss
DB_USERNAME=your_db_username
DB_PASSWORD=your_db_password
```

#### Mail Configuration
```
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password_here
MAIL_FROM_ADDRESS=your_email@gmail.com
```

#### Payment Gateway (Midtrans)
```
MIDTRANS_MERCHANT_ID=your_merchant_id_here
MIDTRANS_CLIENT_KEY=your_client_key_here
MIDTRANS_SERVER_KEY=your_server_key_here
```

#### SMS/WhatsApp Service (Twilio)
```
TWILIO_ACCOUNT_SID=your_twilio_account_sid_here
TWILIO_AUTH_TOKEN=your_twilio_auth_token_here
TWILIO_FROM_PHONE=your_twilio_phone_number
TWILIO_FROM_WHATSAPP=whatsapp:+14155238886
```

### Security Best Practices

1. **Never commit `.env` files** - They are automatically ignored by git
2. **Use strong, unique passwords** for all services
3. **Rotate API keys regularly** especially for production environments
4. **Use environment-specific configurations** - separate keys for development, staging, and production
5. **Enable 2FA** on all third-party service accounts

### File Security

The following files should NEVER be committed to version control:
- `.env`
- `.env.local`
- `.env.production`
- `.env.backup`
- Any file containing actual API keys or passwords

### Reporting Security Issues

If you discover a security vulnerability, please send an email to the development team. Do not create public issues for security vulnerabilities.

### Production Deployment

For production deployment:
1. Set `APP_ENV=production`
2. Set `APP_DEBUG=false`
3. Use production API keys and credentials
4. Enable HTTPS
5. Configure proper database security
6. Use strong APP_KEY (generate with `php artisan key:generate`)
