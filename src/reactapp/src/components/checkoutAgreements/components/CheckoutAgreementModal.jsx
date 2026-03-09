import React from 'react';
import { get as _get } from 'lodash-es';
import { FileCheckIcon } from 'lucide-react';

import { __ } from '../../../i18n';
import { getFormikFieldNameById } from '../utility';
import { CHECKOUT_AGREEMENTS_FORM } from '../../../config';
import useAgreementAppContext from '../hooks/useAgreementAppContext';
import useAgreementModalContext from '../hooks/useAgreementModalContext';
import useAgreementFormikContext from '../hooks/useAgreementFormikContext';

function CheckoutAgreementModal() {
  const { checkoutAgreements } = useAgreementAppContext();
  const { activeModalId, setActiveModalId } = useAgreementModalContext();
  const { setFieldValue } = useAgreementFormikContext();
  const modalAgreement = _get(checkoutAgreements, activeModalId);
  const agreementTitle = _get(modalAgreement, 'name');
  const isContentHtml = _get(modalAgreement, 'isHtml');
  const agreementContent = _get(modalAgreement, 'content');

  const handleAgreeButtonClick = () => {
    const fieldName = `${CHECKOUT_AGREEMENTS_FORM}.${getFormikFieldNameById(
      activeModalId
    )}`;
    setFieldValue(fieldName, true);
    setActiveModalId(false);
  };

  return (
    <div
      role="dialog"
      aria-modal="true"
      aria-labelledby="modal-title"
      className="fixed inset-0 z-10 overflow-y-auto"
    >
      <div className="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div
          className="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75"
          aria-hidden="true"
        />

        <span
          className="hidden sm:inline-block sm:align-middle sm:h-screen"
          aria-hidden="true"
        >
          &#8203;
        </span>

        <div
          style={{ maxWidth: '80%' }}
          className="inline-block text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl agreement-box sm:my-8 sm:align-middle sm:w-full"
        >
          <div className="px-4 pt-5 pb-4 bg-white sm:p-6 sm:pb-4">
            <div className="sm:flex sm:items-start">
              <div className="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto bg-green-100 rounded-full sm:mx-0 sm:h-10 sm:w-10">
                <FileCheckIcon className="w-6 h-6 text-green-600" />
              </div>
              <div className="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                <h3
                  className="text-lg font-medium leading-6 text-gray-900"
                  id="modal-title"
                >
                  {agreementTitle}
                </h3>
                <div className="mt-2 overflow-y-auto lg:h-96">
                  {isContentHtml ? (
                    <div
                      dangerouslySetInnerHTML={{ __html: agreementContent }}
                    />
                  ) : (
                    <p className="text-sm text-gray-500">{agreementContent}</p>
                  )}
                </div>
              </div>
            </div>
          </div>
          <div className="px-4 py-3 bg-gray-50 sm:px-6 sm:flex sm:flex-row-reverse">
            <button
              type="button"
              className="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm"
              onClick={() => setActiveModalId(false)}
            >
              {__('Close')}
            </button>
            <button
              type="button"
              className="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-green-600 border border-transparent rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm"
              onClick={handleAgreeButtonClick}
            >
              {__('I agree')}
            </button>
          </div>
        </div>
      </div>
    </div>
  );
}

export default CheckoutAgreementModal;
