import React from 'react';
import { bool } from 'prop-types';
import { FileIcon } from 'lucide-react';

import AddressOptions from '../../address/components/AddressOptions';
import {
  isCartAddressValid,
  isValidCustomerAddressId,
  prepareFormAddressFromCartAddress,
} from '../../../utils/address';
import { __ } from '../../../i18n';
import LocalStorage from '../../../utils/localStorage';
import { billingAddrOtherOptionField } from '../utility';
import useBillingAddressAppContext from '../hooks/useBillingAddressAppContext';
import useAddressOtherOptions from '../../address/hooks/useAddressOtherOptions';
import useBillingAddressCartContext from '../hooks/useBillingAddressCartContext';
import useBillingAddressFormikContext from '../hooks/useBillingAddressFormikContext';

function BillingAddressOthers({ forceHide }) {
  const {
    setFieldValue,
    submitHandler,
    selectedAddress,
    setSelectedAddress,
    setCustomerAddressSelected,
    billingOtherOptionSelected,
    setBillingAddressFormFields,
  } = useBillingAddressFormikContext();
  const { cartBillingAddress } = useBillingAddressCartContext();
  const { isLoggedIn, customerAddressList } = useBillingAddressAppContext();
  const isCartBillingAddressValid = isCartAddressValid(cartBillingAddress);
  const mostRecentAddressList = LocalStorage.getMostRecentlyUsedAddressList();

  const addressOptions = useAddressOtherOptions({
    selectedAddress,
    cartAddress: cartBillingAddress,
  });

  /**
   * Perform when an other address option is changed.
   */
  const handleOptionChange = (event) => {
    const addressId = event.target.value;
    const customerAddress = customerAddressList[addressId];

    setFieldValue(billingAddrOtherOptionField, addressId);

    if (isCartBillingAddressValid) {
      return;
    }

    setSelectedAddress(addressId);
    setCustomerAddressSelected(isValidCustomerAddressId(addressId));

    if (mostRecentAddressList[addressId]) {
      setBillingAddressFormFields(mostRecentAddressList[addressId]);
    } else if (customerAddress) {
      setBillingAddressFormFields(
        prepareFormAddressFromCartAddress({ ...customerAddress })
      );
    }
  };

  /**
   * Executes when "Bill Here" button is pressed.
   */
  const handleShipToOtherOption = async () => {
    await submitHandler(billingOtherOptionSelected);
    setFieldValue(billingAddrOtherOptionField, '');
  };

  if (!isLoggedIn || forceHide) {
    return null;
  }

  const submitButtonLabel = (
    <>
      <FileIcon className="w-6 h-6 pr-1" />
      <span className="text-xs">{__('Bill Here')}</span>
    </>
  );

  return (
    <AddressOptions
      title={
        isCartBillingAddressValid
          ? __('OTHER ADDRESSES')
          : __('CHOOSE FROM THE ADDRESS LIST')
      }
      options={addressOptions}
      submitButtonLabel={submitButtonLabel}
      inputName={billingAddrOtherOptionField}
      selectedOption={billingOtherOptionSelected}
      actions={{ handleOptionChange, handleShipToOtherOption }}
    />
  );
}

BillingAddressOthers.propTypes = {
  forceHide: bool,
};

BillingAddressOthers.defaultProps = {
  forceHide: false,
};

export default BillingAddressOthers;
