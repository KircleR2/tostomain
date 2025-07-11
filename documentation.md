# Tosto Coffee Application Documentation

## Overview

The Tosto Coffee application is a web-based restaurant management system built with Laravel (PHP) and Vue.js. It features a responsive front-end design for displaying the restaurant's menu, locations, and allowing users to register for a loyalty program. The application follows a typical Laravel MVC architecture with Vue.js components integrated for interactive UI elements.

## Architecture

### Backend (Laravel)

The application is built on Laravel 10.x, a PHP web framework that follows the MVC (Model-View-Controller) pattern:

1. **Models**: Represent database tables and business logic
2. **Views**: Blade templates that render HTML
3. **Controllers**: Handle HTTP requests and return responses

### Frontend

The frontend uses a combination of:

1. **Blade Templates**: Laravel's templating engine for server-side rendering
2. **Vue.js Components**: For interactive UI elements
3. **TailwindCSS**: For styling and responsive design

### Key Components

#### Routes

Routes are defined in `routes/web.php` and `routes/api.php`:

- Web routes handle browser requests and render views
- API routes handle AJAX requests and return JSON

#### Controllers

Controllers are located in `app/Http/Controllers/`:

- `FrontController.php`: Handles public-facing pages (home, menu, etc.)
- `AuthController.php`: Handles authentication (login, register, etc.)
- `DashboardController.php`: Handles authenticated user dashboard

#### Views

Views are located in `resources/views/`:

- `front/`: Public-facing views (home, menu, etc.)
- `back/`: Admin/dashboard views
- `components/`: Reusable Blade components

#### Vue Components

Vue components are located in `resources/js/`:

- `MenuCarrousel.vue`: Displays menu categories in a carousel
- `BranchesCarrousel.vue`: Displays restaurant locations
- `RegisterForm.vue`, `LoginForm.vue`: Authentication forms
- `Dashboard.vue`: User dashboard interface
- `LangModal.vue`: Language selection modal
- `PopupModal.vue`: Promotional popup

#### Value Objects

The application uses value objects to represent static data:

- `app/Values/MenuValues.php`: Contains menu categories and their properties

#### Middleware

Custom middleware for authentication and localization:

- `ClauTokenMiddleware.php`: Handles authentication tokens
- `LocalizationMiddleware.php`: Sets the application locale based on user preferences

## Database Structure

The database includes tables for:

1. Users
2. User lists (for loyalty program)
3. Sessions (for authentication)

## Key Features

1. **Multi-language Support**: Supports English and Spanish
2. **Menu Display**: Dynamic menu display with categories and items
3. **Location Display**: Shows restaurant branches with images
4. **Authentication**: User registration and login
5. **Loyalty Program**: Club Elite membership management

## Migration to Python/Django

### Architecture Mapping

| Laravel Component | Django Equivalent |
|-------------------|-------------------|
| Routes (web.php) | urls.py |
| Controllers | Views (function or class-based) |
| Blade Templates | Django Templates |
| Models | Django Models |
| Middleware | Django Middleware |
| Vue Components | Can be kept as Vue components |
| Laravel Migrations | Django Migrations |

### Step-by-Step Migration Plan

#### 1. Project Setup

```bash
# Create Django project
django-admin startproject tostocoffee
cd tostocoffee

# Create main app
python manage.py startapp core
```

#### 2. Models

Convert Laravel models to Django models:

```python
# core/models.py
from django.db import models
from django.contrib.auth.models import AbstractUser

class User(AbstractUser):
    # Add custom fields from Laravel User model
    phone = models.CharField(max_length=20, blank=True)
    
class UserList(models.Model):
    user = models.ForeignKey(User, on_delete=models.CASCADE)
    name = models.CharField(max_length=255)
    # Other fields...
```

#### 3. URL Configuration

Convert Laravel routes to Django URLs:

```python
# tostocoffee/urls.py
from django.urls import path, include
from django.conf.urls.i18n import i18n_patterns

urlpatterns = i18n_patterns(
    path('', include('core.urls')),
    path('auth/', include('core.auth_urls')),
    # Other URL patterns...
)
```

```python
# core/urls.py
from django.urls import path
from . import views

urlpatterns = [
    path('', views.index, name='front.index'),
    path('menu/', views.menu_index, name='front.menu.index'),
    path('menu/<str:menu>/', views.menu_show, name='front.menu.show'),
    # Other URL patterns...
]
```

#### 4. Views (Controllers)

Convert Laravel controllers to Django views:

```python
# core/views.py
from django.shortcuts import render, redirect
from django.http import Http404
from .values import MenuValues

def index(request):
    return render(request, 'front/home.html')

def menu_index(request):
    return render(request, 'front/menu/index.html', {'is_section': True})

def menu_show(request, menu=None):
    if menu is not None:
        title = ''
        tag = ''
        
        # Convert the switch statement to if-elif
        if menu == MenuValues.BREAKFAST_ALL_DAY['value']:
            title = MenuValues.BREAKFAST_ALL_DAY['label']
            tag = MenuValues.BREAKFAST_ALL_DAY['value']
        elif menu == MenuValues.TO_SHARE['value']:
            title = MenuValues.TO_SHARE['label']
            tag = MenuValues.TO_SHARE['value']
        # ... other menu cases
        else:
            raise Http404("Menu not found")
            
        return render(request, 'front/menu/show.html', {
            'menu': menu,
            'title': title,
            'tag': tag,
            'is_section': True
        })
```

#### 5. Value Objects

Convert PHP value objects to Python:

```python
# core/values.py
class MenuValues:
    BREAKFAST_ALL_DAY = {
        'label': 'Desayunos todo el día',
        'value': 'desayunos-todo-el-dia'
    }
    
    TO_SHARE = {
        'label': 'Para Compartir',
        'value': 'para-compartir'
    }
    
    # ... other menu values
    
    @classmethod
    def get_list(cls):
        return [
            cls.CHEF_SPECIALTIES,
            cls.BREAKFAST_ALL_DAY,
            cls.TO_SHARE,
            # ... other menu items
        ]
```

#### 6. Templates

Convert Blade templates to Django templates:

```html
<!-- templates/front/menu/show.html -->
{% extends 'front/layouts/_layout_main.html' %}
{% block title %}{{ title }} | Menú - {{ site_name }}{% endblock %}
{% block description %}{{ site_name }}{% endblock %}
{% block content %}
  <div id="lang-modal">
    <!-- Vue component will be mounted here -->
  </div>
  <section class="relative">
    <!-- Rest of the template content -->
  </section>
{% endblock %}
```

#### 7. Static Assets

Move and organize static assets:

```
tostocoffee/
  static/
    css/
    js/
    images/
    fonts/
```

#### 8. Vue Components

Keep Vue components and integrate them with Django:

```python
# settings.py
TEMPLATES = [
    {
        # ...
        'OPTIONS': {
            'context_processors': [
                # ...
                'django.template.context_processors.static',
            ],
        },
    },
]
```

#### 9. Middleware

Convert Laravel middleware to Django middleware:

```python
# core/middleware.py
from django.utils import translation
from django.conf import settings

class LocalizationMiddleware:
    def __init__(self, get_response):
        self.get_response = get_response

    def __call__(self, request):
        language = request.COOKIES.get('locale', settings.LANGUAGE_CODE)
        translation.activate(language)
        request.LANGUAGE_CODE = translation.get_language()
        response = self.get_response(request)
        return response
```

#### 10. Authentication

Use Django's built-in authentication system:

```python
# core/auth_views.py
from django.contrib.auth import authenticate, login, logout
from django.shortcuts import render, redirect

def login_view(request):
    if request..method == 'POST':
        username = request.POST.get('username')
        password = request.POST.get('password')
        user = authenticate(request, username=username, password=password)
        if user is not None:
            login(request, user)
            return redirect('back.dashboard')
    return render(request, 'back/login.html')
```

### Challenges in Migration

1. **Template Syntax**: Converting Blade syntax to Django template syntax
2. **Authentication**: Implementing the custom authentication system
3. **Vue Integration**: Ensuring Vue components work properly with Django
4. **Database Migration**: Converting Laravel migrations to Django migrations
5. **Session Handling**: Implementing the session persistence fix for DigitalOcean

## Conclusion

The Tosto Coffee application is a well-structured Laravel application that can be migrated to Django by mapping the components appropriately. While the migration requires significant effort, the similar MVC architecture of both frameworks makes the process straightforward. The Vue.js components can be largely reused with minimal changes, and Django's built-in features can replace most of Laravel's functionality.

By following the migration plan outlined above, the application can be successfully converted to a Python/Django application while maintaining its current functionality and user experience. 