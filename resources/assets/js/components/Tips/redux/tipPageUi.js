const types = {
    TOGGLE_EDIT_MODE_COUPLED_STATISTIC: 'TOGGLE_EDIT_MODE_COUPLED_STATISTIC',
    ADD_SELECTABLE_STATISTICVARIABLES: 'ADD_SELECTABLE_STATISTICVARIABLES'
};

const actions = {
    toggleEditModeCoupledStatistic: id => ({type: types.TOGGLE_EDIT_MODE_COUPLED_STATISTIC, id}),
    addSelectableStatisticVariables: variables => ({type: types.ADD_SELECTABLE_STATISTICVARIABLES, variables})
};


const defaultState = {
    inEditMode: [],
    variableFilters: {acting: [], producing: []},
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


    }
    return state;
};


export {
    types, actions, reducer
}