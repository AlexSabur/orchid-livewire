# Orchid Livewire

[![GitHub Workflow Status](https://github.com/AlexSabur/orchid-livewire/workflows/Run%20tests/badge.svg)](https://github.com/AlexSabur/orchid-livewire/actions)
[![styleci](https://styleci.io/repos/273482753/shield)](https://styleci.io/repos/273482753)

[![Packagist](https://img.shields.io/packagist/v/alexsabur/orchid-livewire.svg)](https://packagist.org/packages/alexsabur/orchid-livewire)
[![Packagist](https://poser.pugx.org/alexsabur/orchid-livewire/d/total.svg)](https://packagist.org/packages/alexsabur/orchid-livewire)
[![Packagist](https://img.shields.io/packagist/l/alexsabur/orchid-livewire.svg)](https://packagist.org/packages/alexsabur/orchid-livewire)

Package description: A Livewire macro for Orchid Platform

## Installation

Install via composer
```bash
composer require livewire/livewire alexsabur/orchid-livewire
```

Publish original assets livewire
```bash
php artisan vendor:publish --tag=livewire:assets
```

### Publish package config

```bash
php artisan vendor:publish --provider="AlexSabur\OrchidLivewire\ServiceProvider"
```

## Usage

### For Table

```php
/**
 * @return array
 */
public function columns(): array
{
    return [
        TD::set('status', __('Name'))
            ->sort()
            ->cantHide()
            ->filter(TD::FILTER_TEXT)
            ->livewire('user.pool-status'),

        TD::set('id', __('ID'))
            ->livewire('user.id', function (User $user) {
                return [
                    'user' => $user
                ];
            }, function (User $user) {
                return "td-user-{$user->id}";
            }),
    ];
}
```

### For Screen

```php
/**
 * Views.
 *
 * @return Layout[]
 */
public function layout(): array
{
    return [
        Layout::livewire('user.pay-status')
            ->only(['user', 'role']), // only user and role from query
        Layout::livewire('foo')
            ->except('role'), // except role from query
        Layout::livewire('baz'), // all from query
    ];
}
```

## Security

If you discover any security related issues, please email alexsabur@live.ru
instead of using the issue tracker.

## Credits

- [Alex Sabur](https://github.com/AlexSabur/orchid-livewire)
- [All contributors](https://github.com/AlexSabur/orchid-livewire/graphs/contributors)

This package is bootstrapped with the help of
[melihovv/laravel-package-generator](https://github.com/melihovv/laravel-package-generator).
