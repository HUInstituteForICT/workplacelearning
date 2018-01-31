const types = {
    TOGGLE_EDIT_MODE_COUPLED_STATISTIC: 'TOGGLE_EDIT_MODE_COUPLED_STATISTIC',
};

const actions = {
    toggleEditModeCoupledStatistic: id => ({type: types.TOGGLE_EDIT_MODE_COUPLED_STATISTIC, id})
};


const defaultState = {
    inEditMode: [],
};

const reducer = (state = defaultState, action) => {


    switch(action.type) {

        case types.TOGGLE_EDIT_MODE_COUPLED_STATISTIC: {
            const inEditMode = [...state.inEditMode];
            if(inEditMode.includes(action.id)) {
                inEditMode.splice(inEditMode.indexOf(action.id, 1));
            } else {
                inEditMode.push(action.id);
            }
            return {...state, inEditMode};
        }
    }
    return state;
};


export {
    types, actions, reducer
}