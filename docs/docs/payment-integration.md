# Payment Integration
In Hyvä React Checkout, the payment options will be shown as radio inputs by default. You can choose the option and place the order, and everything works out of the box.

But showing just a radio input box and allowing you to select/deselect them is not what you needed for all payment options. Some payment methods need different behavior. Also, some payment methods need a separate flow when we perform the "place order" action. To help you out in such cases, Hyvä React Checkout already possesses some superpowers, which we will see in a short time.

## Payment Supports
There are numerous payment service providers (PSP) out there. In a site, we opt one of the PSP by adding its Magento 2 extension and configure it. These PSPs will support many modes of payment methods. Due to this, it will be a cumbersome process to support all the PSPs out there.

Due to the above reason, we are not keeping payment components as part of Hyvä React Checkout. But we will be maintaining separate payment repositories that would support most common PSPs which will hold the custom payment method components which you can use with Hyvä React Checkout seamlessly.

Below, we are listing the payment repositories, and each repository will have specific instructions about its usage.

- Amazon - [friends-of-hyva/magento2-react-checkout-amazon-pay](https://github.com/friends-of-hyva/magento2-react-checkout-amazon-pay)
- easyCredit-Ratenkauf [netzkollektiv/magento2-react-checkout-easycredit](https://github.com/netzkollektiv/magento2-react-checkout-easycredit)
- Ivy - [getivy/magento-2-hyva-react-checkout](https://github.com/getivy/magento-2-hyva-react-checkout)
- Klarna - [friends-of-hyva/magento2-react-checkout-klarna](https://github.com/friends-of-hyva/magento2-react-checkout-klarna)
- Mollie official [mollie/magento2-hyva-react-checkout](https://github.com/mollie/magento2-hyva-react-checkout)
- Mollie - [MdnAgency/magento2-hyva-checkout-mollie](https://github.com/MdnAgency/magento2-hyva-checkout-mollie)
- MultiSafepay - [MultiSafepay/magento2-hyva-checkout-multisafepay](https://github.com/MultiSafepay/magento2-hyva-checkout-multisafepay)
- Offline payment methods - [friends-of-hyva/magento2-react-checkout-offline-payments](https://github.com/friends-of-hyva/magento2-react-checkout-offline-payments)
- Payone - [friends-of-hyva/magento2-react-checkout-payone](https://github.com/friends-of-hyva/magento2-react-checkout-payone)
- Paypal Express - [friends-of-hyva/magento2-react-checkout-paypal-express](https://github.com/friends-of-hyva/magento2-react-checkout-paypal-express)
- Sage Pay Suite - [ebizmarts/magento2-hyva-checkout-sage-pay-suite](https://github.com/ebizmarts/magento2-hyva-checkout-sage-pay-suite)
- Stripe - [eltrino/magento2-hyva-checkout-stripe](https://github.com/eltrino/magento2-hyva-checkout-stripe)


This is a community effort. So we welcome all of you to help out to grow the above list. The _payone_ repository is an ideal place to look into if you need to create a payment method integration repository.
_____________
## Payment Configurations and Its Usage
Depending upon the payment methods, you may have some special configuration settings in your store. Usually, you do this under `Stores > Configuration`. If you are familiar with the default Magento 2 checkout page, then you may know that Magento 2 passes this configuration via a global variable which you can find at `window.checkoutConfig`.

To collect these checkout configurations, we have a ViewModel (`Hyva\ReactCheckout\ViewModel\CheckoutConfigProvider`) available in the module and we are passing this configuration through the root DOM element.

See the layout file where we injecting the view model.

File: `src/view/frontend/layout/hyva_reactcheckout_index.xml`

```
<?xml version="1.0"?>
<page xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" layout="1column" xsi:noNamespaceSchemaLocation="urn:magento:framework:View/Layout/etc/page_configuration.xsd">
    ...
    <body>
        <referenceContainer name="content">
            <block
                cacheable="false"
                name="checkout.container"
                template="Hyva_ReactCheckout::react-container.phtml"
            >
                <arguments>
                    <argument
                        name="checkout_config_provider"
                        xsi:type="object">Hyva\ReactCheckout\ViewModel\CheckoutConfigProvider</argument>
                </arguments>
            </block>
        </referenceContainer>
        ...
    </body>
</page>
```


See the data attribute `data-checkout_configuration` through which we are passing this configuration.

    File: `src/view/frontend/templates/react-container.phtml`


        <?php
        ...
        /** @var \Magento\Framework\Escaper $escaper */
        /** @var \Hyva\ReactCheckout\ViewModel\CheckoutConfigProvider $configProvider */
        $configProvider = $block->getCheckoutConfigProvider();
        ...
        ?>
        <div
            id="react-checkout"
            ...
            data-checkout_config="<?= $escaper->escapeHtmlAttr($configProvider->getConfig())  ?>"
        >
        ...
        </div>


Now the react app has access to the payment configurations, and it can be used in the application according to your need.

## Custom Payment Renderer
As already stated, by default, all payment methods appear as a radio input. But Hyvä React Checkout allows using a custom renderer for any payment methods.

You can either develop your own custom payment renderer within the `reactapp`or use an existing custom payment renderer repo within Hyvä React Checkout.

#### How To Create My Own Custom Payment Renderer
Lets say we want to create a custom payment renderer for paypal-express solution. This is how you do this in Hyvä React Checkout.

Create following folders/ files in `src/reactapp/src/paymentMethods`

```
src/reactapp/src/paymentMethods/
|
|____paypalExpress/
    |
    |____renderers.js
    |
    |____src/components/PaypalExpress.jsx
```
You need at least two files in order to properly create a custom payment renderer.

1. The custom payment render component. This is a react component. This react component will be used to render the payment method. In the shown example `src/components/PaypalExpress.jsx` is what out custom payment renderer component.
2. `renderers.js` file that configures the payment renderer component. The content of this file somewhat looks like below

    File: `src/reactapp/src/paymentMethods/paypalExpress/renderers.js`

        import PaypalExpress from './src/components/PaypalExpress';

        export default {
          paypal_express: PaypalExpress,
        };

    So it exports an object. Each key specifies the payment method code (`paypal_express`) and its value is the custom payment render component (`PaypalExpress`).


#### How To Use A Custom Payment Renderer Repo With Hyvä React Checkout

You need to specify your custom payment repo in the `package.json` file as shown below:

File: `src/reactapp/package.json`
```
"config": {
    "paymentMethodsRepo": {
        "payone": "git@github.com:friends-of-hyva/magento2-react-checkout-payone.git"
    }
},
```
As you can see above, we are configuring `payone` custom payment solution under `config.paymentMethodsRepo`. With this change in your `package.json` and if you run `npm install`, then your repo will be cloned into `src/reactapp/src/paymentMethods` directory and properly configured over there.

### How Custom Payment Renderer Works
It all starts from the react component `<PaymentMethodMemorized />`. This component is responsible for showing the entire payment method section. See its implementation

File: `src/reactapp/src/components/paymentMethod/PaymentMethodMemorized.jsx`
```
import React, { useEffect, useState } from 'react';
...
import customRenderers from '../../paymentMethods/customRenderers';

const PaymentMethodMemorized = React.memo(({ formikData }) => {
  ...
  return (
    <PaymentMethodFormManager formikData={formikData}>
      <Card classes={isPaymentAvailable ? '' : 'opacity-75'}>
        <ToggleBox show title={__('Payment Methods')}>
          {isPaymentAvailable ? (
            <PaymentMethodList methodRenderers={customRenderers} />
          ) : (
            <NoPaymentMethodInfoBox />
          )}
        </ToggleBox>
      </Card>
    </PaymentMethodFormManager>
  );
}

export default PaymentMethodMemorized;
```
Here `customRenderers` holds all the custom payment renderers available. This value is an object where its keys will be payment method code and values will be renderer component. `renderers` state is then passing to `<PaymentMethodList />` component. This component will loop through each payment method and if a custom payment renderer defined for a payment, then it will use that custom payment renderer for that payment method; else, it will render the payment method as radio button (default behaviour).

`customRenderers` object which is coming from the file `src/paymentMethods/customRenderers.js` is an auto-generated file. This file is updated when you have custom payment renderer repositories setup in your `package.json` file and you perform the installation `npm install`. The chance to alter this file happens only in those situations where you have your own custom payment renderers and you want to make them available in the payment method section. In that case, you need to manually add your custom payment renderer in that file.

#### How Custom Payment Renderer Interacts on Place Order Action
If your payment method needs to alter the behaviour of place order action, then Hyvä React Checkout provides some features out of the box to help you out.

File: `src/reactapp/src/context/Form/CheckoutFormProvider.jsx`
```
...
function CheckoutFormProvider({ children }) {
  ...
  const [paymentActionList, setPaymentActions] = useState({});
  ...
  const registerPaymentAction = useCallback(
    (paymentMethodCode, paymentMethodAction) => {
      setPaymentActions(actions => ({
        ...actions,
        [paymentMethodCode]: paymentMethodAction,
      }));
    },
    [setPaymentActions]
  );
  ...
}
...
```
Here `CheckoutFormProvider` is one of the super parent components that wraps react app. As you can see in the code it has a state `paymentActionList` which can be populated with any custom payment action you want to perform when the "place order" action happens. See the `registerPaymentAction` function. This function is available for all the child components and thus a custom payment method component can use it to register a custom "place order" behaviour.

Suppose you have a custom payment method component `PayOneCreditCard` and it looks like below
```
function PayOneCreditCard({ method, selected, actions }) {
  const { registerPaymentAction } = useCheckoutFormContext();

  // custom "place order" submit action
  const paymentSubmitHandler = useCallback(
    async values => {
      console.log({ values });
      alert('yeeeha');
    },
    []
  );

  // registering custom "place order" action for the payment method
  useEffect(() => {
    registerPaymentAction('payone_creditcard', paymentSubmitHandler);
  }, [registerPaymentAction, paymentSubmitHandler]);

  return (
    // custom payment method JSX here
  );
}
```

So in the `useEffect`, we are registering custom place order action for the payment method. Now when the user opted `payone_creditcard` as the payment option and then click on the "Place Order" button, it will use the `paymentSubmitHandler` in this component to process the order. So now the payment method needs to convert the quote to order according to its need and then redirect the user to the success page.

### Custom Payment Method Best Practices

1. Define dedicated custom hooks for accessing global states such as `App`, `Cart` or `Form` within the payment method itself. There are hooks available in other places to get the global state. But, it is always better stay away from those custom hooks and define and use dedicated custom hooks within the payment method.

    So if you want to get an `App` action such as `setPageLoader` within your payment method, then your can do the following to retrieve it.

    File: `src/reactapp/src/paymentMethods/<yourPaymentMethod>/src/hooks/usePayOneAppContext.js`

        import { useContext } from 'react';

        import AppContext from '../../../../context/App/AppContext';

        export default function usePayOneAppContext() {
          const [, { setPageLoader }] = useContext(AppContext);

          return {
            setPageLoader,
          };
        }


    Now use `usePayOneAppContext` hook inside your `yourPaymentMethod`.

  2. Define your custom API methods within the payment method itself.

      Hyvä React Checkout holds all of its api methods inside `src/reactapp/src/api/` directory. The components does not use any api methods defined here directly. Instead we are passing them through `AppContext` or `CartContext`. So if an API method needed in your payment method is already defined in the app, then access them through the corresponding context and use it with your payment method.

      But in some cases, your payment method needs to use its own custom api methods. In this case, it should be defined inside `src/reactapp/src/paymentMethods/<yourPaymentMethod>/src/api/` directory. When you define your custom API methods, it will be good to follow the conventions we already using in `src/reactapp/src/api/`.

      You can use your custom API methods directly inside the components. No need to setup a context and passing the api method through that context. But if your payment method is so complicated and you need a context layer to manage it effectively, then feel free to adopt that approach too.

  3. Most of the custom payment methods need to provide custom data as part of placing the order. In the Luma checkout, they send this extra information either via `additional_data` and/or via `extension_attributes` which are part of REST API method `save-payment`. In order to replicate the same in Hyvä React Checkout, it is vital to rely on the above REST API method. So there is a general hook `src/reactapp/src/hook/usePerformPlaceOrderByREST.js` available and it can be handy in payment integration in order to place an order.
