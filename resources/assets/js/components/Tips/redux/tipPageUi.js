const types = {
    TOGGLE_EDIT_MODE_COUPLED_STATISTIC: 'TOGGLE_EDIT_MODE_COUPLED_STATISTIC',
    ADD_SELECTABLE_STATISTICVARIABLES: 'ADD_SELECTABLE_STATISTICVARIABLES',
    ERROR_ADD: 'ERROR_ADD',
    ERROR_REMOVE: 'ERROR_REMOVE',
};

const actions = {
    toggleEditModeCoupledStatistic: id => ({type: types.TOGGLE_EDIT_MODE_COUPLED_STATISTIC, id}),
    addSelectableStatisticVariables: variables => ({type: types.ADD_SELECTABLE_STATISTICVARIABLES, variables}),
    errorAdd: (id, error) => ({type: types.ERROR_ADD, id, error}),
    errorRemove: id => ({type: types.ERROR_REMOVE, id}),
};


const defaultState = {
    inEditMode: [],
    variableFilters: {acting: [], producing: []},
    errors: {}
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

        case types.ADD_SELECTABLE_STATISTICVARIABLES: {
            return {...state, variableFilters: action.variables}
        }

        case types.ERROR_ADD: {
            return {...state, errors: {...state.errors, [action.id]: action.error}};
        }

        case types.ERROR_REMOVE: {
            const errors = {...state.errors};
            delete errors[action.id];
            return {...state, errors};
        }
    }
    return state;
};


export {
    types, actions, reducer
}