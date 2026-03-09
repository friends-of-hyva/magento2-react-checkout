import React from 'react';
import { get as _get } from 'lodash-es';
import { InfoIcon } from 'lucide-react';

import { Checkbox } from '../../../../code/common/Form';
import {
  useAgreementAppContext,
  useAgreementModalContext,
  useAgreementFormikContext,
} from '../../../../code/checkoutAgreements/hooks';
import { _objToArray } from '../../../../../utils';
import { getFormikFieldNameById } from '../utility';

function CheckoutAgreementsForm() {
  const { checkoutAgreements } = useAgreementAppContext();
  const { setActiveModalId } = useAgreementModalContext();
  const { fields, agreementsValues } = useAgreementFormikContext();

  return _objToArray(checkoutAgreements).map((agreement, index) => {
    const { id: agreementId, isAutomatic, label } = agreement;
    const fieldName = getFormikFieldNameById(agreement.id);
    const isAgreed = !!_get(agreementsValues, fieldName);

    if (!isAutomatic) {
      return (
        <div key={agreementId} className="flex flex-wrap mt-3">
          <div>
            {fields[fieldName] && (
              <Checkbox
                required
                label={label}
                isChecked={isAgreed}
                name={fields[fieldName]}
              />
            )}
          </div>
          <div className="mt-3 ml-2 cursor-pointer">
            <InfoIcon
              className="w-4 h-4 text-primary-600"
              onClick={() => setActiveModalId(agreementId)}
            />
          </div>
        </div>
      );
    }

    return (
      <div key={agreementId} className="flex flex-wrap mt-2">
        <div>
          <div
            role="button"
            tabIndex={index}
            onClick={() => setActiveModalId(agreementId)}
            className="mt-3 ml-6 text-sm cursor-pointer"
            onKeyDown={(event) => {
              if (event.key === 'Enter') {
                setActiveModalId(agreementId);
              }
            }}
          >
            {label}
          </div>
        </div>
        <div className="mt-4 ml-2 cursor-pointer">
          <InfoIcon
            className="w-4 h-4 text-primary-600"
            onClick={() => setActiveModalId(agreementId)}
          />
        </div>
      </div>
    );
  });
}

export default CheckoutAgreementsForm;
