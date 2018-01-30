import {coupledStatistic} from "../../../Schema";

const types = {
    ADD_ENTITIES: 'ADD_ENTITIES',
    UPDATE_ENTITY: 'UPDATE_ENTITY',
    STATISTIC_COUPLED: 'STATISTIC_COUPLED',
    ADD_COUPLED_STATISTIC_TO_TIP: 'ADD_COUPLED_STATISTIC_TO_TIP'
};

const actions = {
    addEntities: entities => ({type: types.ADD_ENTITIES, entities}),
    updateEntity: (name, key, entity) => ({type: types.UPDATE_ENTITY, name, key, entity}),
    statisticCoupled: coupledStatistic => ({type: types.STATISTIC_COUPLED, coupledStatistic}),
    addCoupledStatisticToTip: (coupledStatisticId, tipId) => ({type: types.ADD_COUPLED_STATISTIC_TO_TIP, coupledStatisticId, tipId})
};

const defaultState = {
    tips: {},
    coupledStatistics: {},
    cohorts: {},
    statistics: {},
    statisticVariables: {},
};

const reducer = (state = defaultState, action) => {

    switch (action.type) {

        case types.ADD_ENTITIES: {
            const newEntities = {...state};
            Object.keys(action.entities).forEach(entityKey => newEntities[entityKey] = {...state[entityKey], ...action.entities[entityKey]});
            return newEntities;
        }
        case types.UPDATE_ENTITY: {
            return {...state, [action.name]: {...state[action.name], [action.key]: action.entity}};
        }

        case types.ADD_COUPLED_STATISTIC_TO_TIP: {
            const tip = {...state.tips[action.tipId]};
            tip.coupled_statistics = [...tip.coupled_statistics, action.coupledStatisticId];
            return {
                ...state,
                tips: {...state.tips, [tip.id]: tip}
            };

        }

    }

    return state;
};


export {
    types, actions, reducer
}