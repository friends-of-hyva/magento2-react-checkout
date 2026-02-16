
# Hyvä Themes - React Checkout

# [![Hyvä Themes](./assets/img/hyva-wordmark.svg)](https://hyva.io/)

## [hyva-themes/magento2-react-checkout](https://github.com/friends-of-hyva/magento2-react-checkout)
Hyvä React Checkout is an open-sourced Magento 2 module which provides a fast performing checkout page powered by React (a modern javascript library).

Compatible with Magento 2.3.4 and higher.

## What does it do?
It loads a React Checkout at `[store-url]/hyva/reactcheckout`. When enabled in `hyva_react_checkout/general/enable` it replaces the default checkout.

It depends on these two variables in `localstorage`:

- `signin_token` to the customer section
- `cartId` to the cart section

For Hyvä Themes or default Magento frontend, `hyva-themes/magento2-graphql-tokens` is needed to add these tokens to the customerSections.
## What it is not
Hyvä React Checkout is not a drop-in replacement for the default Luma checkout; it does not have feature parity. Rather, it provides you a base on which you can build your own custom checkout for your site. It is a powerful tool with which you can develop the checkout in the most efficient way.

It basically avoids the frustration you have when you want to customize the Luma checkout by making the development process super fun.

It gives you a fast performing checkout as the final bundle size of the javascript is less than 90kb.

## What are the benefits?

In the default Luma theme in Magento 2, the checkout page is completely rendered using javascript. Unfortunately, the uiComponents are difficult to comprehend and developers are struggling when it comes to customize the checkout page. Also, the Luma checkout is too slow since it requires more than 400 javascript and template files needs to be download on the page load.

In Hyvä React Checkout, the UI is constructed using React Components. This means, if you are familiar with the React, then the development is super fun and enjoyable. It is easy to bring any kind of customizations. It loads only a single javascript file in order to render the entire checkout page. So the checkout page loads fast and performs well.

By default, the checkout works out of box. But, in most of the time you need to customize the checkout page to bring some custom functionalities or modifying existing checkout behaviour or change the look and feel. To make this tedious process simpler, you can launch the embedded ReactApp as a stand alone App and by providing a cartId, you can work on the checkout page exclusively. This development mode supports by Hot Module Reloading (HMR) which will enable us to see the changes we have been making on the code almost immediately reflect on the browser and that will eventually going to save a huge amount of time in the development.

## Where can we use it?

Hyvä React Checkout can be used in any Luma based themes or with Hyvä Theme directly. With some modifications, we can also use this with a headless approach too.

!!! Note "In a Luma based theme"
    You would encounter some CSS problems. This is because we are using Tailwind CSS for the styling of the checkout page and since this is a different CSS approach that is being used in the Luma theme, some CSS conflicts should be expected.

!!! Note "In a headless approach"
    There are some configurations we are passing through the data attribute via HTML DOM. This needs to be implemented in the headless approach to make it work there.

## Dependencies

In order to use Hyvä React Checkout, you would require the following

- React knowledge. This is essential to work with Hyvä React Checkout as it is completely build with React.
- A magento 2 site with graphql API.
- Nodejs >= 10 is required in order to make it work.

## Technologies/ Terminologies Used

The following technologies are used in the Hyvä React Checkout.

 - **React** : React is used to build the UI.
 - **Tailwind CSS** : This is used for styling the react components.
 - **Formik** : This is used to store the checkout form states.A popular library in React realm.
 - **Yup** : This is used for the validation of the checkout form. It works well with Formik.
 - **GraphQL** : It uses the Magento 2 GraphQL API in order to update the quote from the checkout page.
