const types = {
    UPDATE_COUPLE_PROPERTY: 'UPDATE_COUPLE_PROPERTY'
};
const actions = {
    updateCoupleStatisticFormProperty: (property, value) => ({type: types.UPDATE_COUPLE_PROPERTY, property, value})
};



const defaultState = {
    statistic: '',
    comparisonOperator: 0,
    threshold: 0.5,
    multiplyBy100: false,
};

const reducer = (state = defaultState, action) => {

    switch(action.type) {

        case types.UPDATE_COUPLE_PROPERTY:
            return {...state, [action.property]: action.value}


    }


    return state;
};

export {
    types, actions, reducer
}