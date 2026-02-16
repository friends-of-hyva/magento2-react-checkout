# The setup and usage instructions

## Instruction to launch the React App

Below provides the minimal steps to launch the react app embedded in Hyvä Checkout 2.0.

1. Clone the repository


        git clone git@github.com:friends-of-hyva/magento2-react-checkout.git hyva-checkout

2. Switch to 2.0 branch

        git checkout 2.0-develop

3. CD into the react app

        cd hyva-checkout/src/reactapp

4. Install dependencies

        npm install

5. Specify the Magento backend URL via `.env`. Please see `env.example` for more details. You need to at least provide the `HYVA_BASE_URL` data to make it work.

        HYVA_BASE_URL=http://your-magento-backend.com

6. Launch the application

        npm run dev

7. Go to your browser and open the url `http://localhost:3000`. You will see your checkout.

## Instructions to build the app

1. CD into the react app directory

        cd hyva-checkout/src/reactapp

2. Perform build (provided you already installed the dependencies)

When you build, there are two files generated and stored into `src/view/frontend/web/` directory.

- `react-checkout.es.min.js` - the build of the react app
- `style.css` - the CSS file needs to be used.

When you use this module as a magento extension, these two files will be used in your checkout. So you don't need to do anything further here.

## Instructions to use custom payment integrators

Hyvä Checkout has a concept of custom payment renderers. It allows you to introduce an external repository that deals with custom payment integration which works with Hyvä Checkout. In the `2.0.x` version also the same concept does exist.

To add custom payment renderer, you need to follow the instructions provided in the repository. In a nutshell, you are adding them via `package.json`

```
"config": {
    "paymentMethodsRepo": {
        "payone": "git@github.com:friends-of-hyva/magento2-react-checkout-payone.git"
    }
},
```

!!! Note "You should deal with conflicts"
    The custom payment renderers supports `1.0.x` version of Hyvä Checkout. This can introduce some issues in the app as the referred components or hooks inside the payment renderers may not be available due to the directory structure changes in the `2.0.x` version of the Hyvä Checkout. So it is your responsibility to fix those issues in the payment renderers repo. If there are `2.0.x` versions exists in the custom payment renderer repositories, then try to use it as these problems probably fixed in those branches.
