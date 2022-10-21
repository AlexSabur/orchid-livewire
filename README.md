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
# for Orchid 12, 13
composer require livewire/livewire alexsabur/orchid-livewire:^5.2

# for Orchid 11
composer require livewire/livewire alexsabur/orchid-livewire:^4.0

# or for Orchid 10
composer require livewire/livewire alexsabur/orchid-livewire:^3.0
```

Publish orchid-livewire assets
```bash
php artisan vendor:publish --tag=orchid-livewire-assets
```

Publish original assets livewire (optional)
```bash
php artisan vendor:publish --tag=livewire:assets
```

Publish package config (optional)

```bash
php artisan vendor:publish --provider="AlexSabur\OrchidLivewire\ServiceProvider"
```

## Usage

### For Table and Sight

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

        // livewire will send an "email" with the key "email"
        TD::set('email', __('email'))
            ->livewire('user-email', key: fn (User $user) => "td-user-email-{$user->id}"),

        // livewire will be passed the model under the key 'user'
        TD::set('some_data', __('some data'))
            ->livewire('some-component', 'user', fn (User $user) => "td-some-data-{$user->id}"),

        TD::set('id', __('ID'))
            ->livewire('user.id', function (User $user) {
                return [
                    'user' => $user
                ];
            }, fn (User $user) => "td-user-{$user->id}"),
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
        // With only user and role from query
        Layout::livewire('user.pay-status')
            ->only(['user', 'role']),

        // With except role from query
        Layout::livewire('foo')
            ->except('role'),

        // Without data from query
        Layout::livewire('baz')
            ->empty(),

        // With all from query
        Layout::livewire('baz'),
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
