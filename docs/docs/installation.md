# Setting up Hyvä React Checkout

This checkout is not intended to be installed as plug-and-play. It should be considered as a toolbox to build your own checkout based on your needs. A developer with ReactJS knowledge will be required.

Ideally, you would use this checkout with your own version of this module. There are number of ways you can setup your own version of this module which is described in detail in the section [Customization Steps](#customization-steps)

## Installation

If you want to install the checkout as a demo or just try it out, install it directly as follows:

1. Install via composer

    a. Via Packagist:

        composer require hyva-themes/magento2-react-checkout

    b. Via composer

        composer config repositories.hyva-themes/magento2-react-checkout git git@github.com:friends-of-hyva/magento2-react-checkout.git

        composer require hyva-themes/magento2-react-checkout


2. Enable module
    ```
    bin/magento setup:upgrade
    ```
3. Apply theme specific changes (if any). You can find more details of this further down.

## Additional Required Steps - Hyvä Themes

Both Hyvä Themes and Hyvä React Checkout use TailwindCSS for styling. In a Hyvä Themes based site, it does not make sense to include `Hyva_Checkout::css/styles.css` stylesheet because of the following reasons.

1. It adds an additional style sheet in the site. One more style sheet means one more render-blocking resource in the browser.
2. The style sheet holds lot of duplicated styles since both using the TailwindCSS.

Due to these reasons, it is better to add Hyvä React Checkout styled components in the TailwindCSS purge directory list of your Hyvä Themes based site. You can find more details of this in the official documentation of Hyvä Themes: [**Tailwind Purging settings**](https://docs.hyva.io/doc/tailwind-purging-settings-goVaHblLAR)

Below is the bare minimum steps you need to do in order to achieve it.

1. You need to setup a child theme in your site. It should be inherited from `Hyva/default` theme. For the sake of simplicity, I am calling this child theme as `Hyva/custom`.

Here's how to quickly set it up from the command line;

```bash
mkdir -p app/design/frontend/Hyva/custom
cp -R vendor/hyva-themes/magento2-default-theme/web app/design/frontend/Hyva/custom/web
cp vendor/hyva-themes/magento2-default-theme/registration.php app/design/frontend/Hyva/custom
cp vendor/hyva-themes/magento2-default-theme/theme.xml app/design/frontend/Hyva/custom
sed -i 's/Hyva\/reset/Hyva\/default/' app/design/frontend/Hyva/custom/theme.xml
sed -i 's/Default/Custom/' app/design/frontend/Hyva/custom/theme.xml
sed -i 's/Hyva\/default/Hyva\/custom/' app/design/frontend/Hyva/custom/registration.php
bin/magento setup:upgrade
```

2. Copy over the web directory from `vendor/hyva-themes/magento2-default-theme/web/` to your own theme `app/design/frontend/Hyva/custom/web/` (already done if you followed previous instructions).
3. Edit the `tailwind.config.js` file inside your theme and add/uncomment following lines inside purge directory list.

```
module.exports = {
    ...
    purge: {
        content: [
            ...
            // Hyva checkout components
            '../../../../../../../vendor/hyva-themes/magento2-react-checkout/src/reactapp/src/**/*.jsx',
            '../../../../../../../vendor/hyva-themes/magento2-react-checkout/src/view/frontend/templates/react-container.phtml',
            ...
        ]
    }
}
...
```

4. Install dependencies and build the new CSS.

```
npm --prefix app/design/frontend/Hyva/custom/web/tailwind/ install
npm --prefix app/design/frontend/Hyva/custom/web/tailwind/ run build-prod
```

!!! Note "For Hyva_CheckoutExample template users"
    If you are using the [**Hyva_CheckoutExample**](https://github.com/friends-of-hyva/magento2-react-checkout-example) template for customizing Hyvä React Checkout, then you are required to include the React components in that module too in the above purge list.

With these changes in place, Hyvä React Checkout styles will be also considered by your theme and thus you will see a stylized checkout page in your store.

## Additional Steps - Luma Theme

For Luma theme based site, Hyvä React Checkout module works out of box. This is because the stylesheet responsible for the Hyvä React Checkout styles `Hyva_ReactCheckout::css/styles.css` is already included through a layout update.

!!! Warning "In a Luma based theme"

    You would encounter some CSS problems. This is because we are using Tailwind CSS for the styling of the checkout page and since this is a different CSS approach that is being used in the Luma theme, some CSS conflicts should be expected.

## Additional Steps - Headless Solutions

You are required to include the stylesheet `Hyva_ReactCheckout::css/styles.css` and the js file `Hyva_ReactCheckout::js/react-checkout.js` in your checkout page. These two static resources are already included through layout update. But, if your headless solution does not respect the layout update, then it is your job to include them in your checkout page.

You need to pass the checkout related configurations (from Magento 2 backend) to the ReactApp. This is currently managed through data attributes in the below HTML DOM element.

File:  `src/view/frontend/templates/react-container.phtml`

```
<div
    id="react-checkout"
    data-base_url="<?= $block->getBaseUrl() ?>"
    data-static_file_path="<?= $block->getViewFileUrl('Hyva_ReactCheckout/js') ?>"
    data-checkout_config="<?= $escaper->escapeHtmlAttr($configProvider->getConfig()) ?>"
>
```
Same thing you should do in your headless approach.

Ideally, these information must be managed through a custom graphql query. This way, it would be compatible with any type of frontend solution. It will be resolved in a future version of Hyvä React Checkout.

## Configuration

Once the module is available in your site, you can see the checkout page at the url `[store-url]/hyva/reactcheckout`.

If you want to make Hyvä React Checkout as your default checkout solution, then enable the below configuration in the Magento2 Backend:

`HYVA THEMES->Checkout->General Settings->Enable`

The configuration path is `hyva_react_checkout/general/enable`.

After this, you would be able to see your checkout when you navigate to `[store-url]/checkout`.

## Customization Steps

In almost all cases, you need to customize the checkout. When it comes to customizing Hyvä React Checkout, you can basically have three approaches. You should opt one of the approaches given below which best suits your needs.

- The recommended way of customizing Hvyä Checkout would be using the [**Magento2 Checkout Example Template**](https://github.com/friends-of-hyva/magento2-react-checkout-example). In this approach, you are keeping the Hyvä React Checkout as a composer dependency and never touches it. The customization is done via another module which you setup at `app/code/` directory using the template given above. In a nutshell, this template includes a custom webpack configuration which enables you to copy over the React Components you really need to alter. This way, the customization will be kept differently and thus eventually it would be easy for you to upgrade Hyvä React Checkout and adopt the changes into your customization if needed.

- The second approach would be to fork the original Hyvä React Checkout repository and use the forked version in your site. This way, you can keep and versioning the customization you are making in that forked repository.

- The third approach would be setting up Hyvä React Checkout inside `app/code` directory. To do this, you need to create the directory `app/code/Hyva/ReactCheckout` and then copy the content of `src/` directory of Hyvä React Checkout module over there. This will also allow you to track the customizations.
