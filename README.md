# Hyvä Themes - React Checkout

A Highly Customizable Checkout for Magento 2, Built with React.

# [![Hyvä Themes](https://github.com/friends-of-hyva/magento2-react-checkout/blob/documentation/docs/images/logo-hyva.svg)](https://hyva.io/)

## friends-of-hyva/magento2-react-checkout

![Supported Magento Versions][ico-compatibility]

Compatible with Magento 2.3.4 and higher.

## Documentation

You can find the documentation here: https://friends-of-hyva.github.io/magento2-react-checkout/

## What does it do?
It loads a React Checkout at `[store-url]/hyvareactcheckout/reactcheckout`. When enabled in `hyva_react_checkout/general/enable` it replaces the default checkout.

It depends on these two variables in localstorage:
 - `signin_token` to the `customer` section
 - `cartId` to the `cart` section

For Hyvä Themes or default Magento frontend, `hyva-themes/magento2-graphql-tokens` is needed to add these tokens to the `customerSections`.

## Installation

**Please note:** this checkout is not intended to be installed as *plug-and-play*. It should be considered as a toolbox to build your own checkout based on your needs. A developer with ReactJS knowledge will be required.

Ideally, you would use this checkout with your own version of this module: https://github.com/friends-of-hyva/magento2-react-checkout-example
The Checkout Example uses an extension mechanism that allows you to build your own checkout based on this repo.

If you want to install the checkout as a demo or just try it out, install it directly as follows:

1. Install via composer

    Via Packagist:
    ```
    composer require hyva-themes/magento2-react-checkout
    ```

    Via Github:
    ```
    composer config repositories.hyva-themes/magento2-react-checkout git git@github.com:friends-of-hyva/magento2-react-checkout.git
    composer require hyva-themes/magento2-react-checkout
    ```
2. Enable module
    ```
    bin/magento setup:upgrade
    ```

To fully customize the checkout to your needs, either clone this repo, or use https://github.com/friends-of-hyva/magento2-react-checkout-example.

Read the full documentation here: https://friends-of-hyva.github.io/magento2-react-checkout/
## Configuration

In the Magento Backend Configuration:

`HYVA THEMES->React Checkout->General Settings->Enable`

The configuration path is `hyva_react_checkout/general/enable`

## Credits

This Checkout has been built in corporation - and with the support of - our main partner, integer_net.

# [![integer_net GmbH](https://github.com/friends-of-hyva/magento2-react-checkout/blob/documentation/docs/images/logo-integernet-green.png)](https://integer-net.de)

In particular, [Rajeev K Tomy][link-author] has been a monumental in the development of this Checkout.

- [Rajeev K Tomy][link-author]
- [Willem Wigman][link-author2]
- [integer_net GmbH][link-company1]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.txt) for more information.

[ico-compatibility]: https://img.shields.io/badge/magento-%202.3%20|%202.4-brightgreen.svg?logo=magento&longCache=true&style=flat-square

[link-author]: https://github.com/progammer-rkt
[link-author2]: https://github.com/wigman
[link-company1]: https://integer-net.com
[link-contributors]: ../../contributors
