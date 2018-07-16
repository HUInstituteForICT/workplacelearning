const types = {
    ADD_ENTITIES: 'ADD_ENTITIES',
    UPDATE_ENTITY: 'UPDATE_ENTITY',
    REMOVE_ENTITY: 'REMOVE_ENTITY',
    ADD_COUPLED_STATISTIC_TO_TIP: 'ADD_COUPLED_STATISTIC_TO_TIP',
    DECOUPLE_STATISTIC_FROM_TIP: 'DECOUPLE_STATISTIC_FROM_TIP',
    REMOVE_TIP: 'REMOVE_TIP',
    STATISTIC_DELETED: 'STATISTIC_DELETED',
    ADD_MOMENT_TO_TIP: 'ADD_MOMENT_TO_TIP',
    DECOUPLE_MOMENT_FROM_TIP: 'DECOUPLE_MOMENT_FROM_TIP',
};

const actions = {
    addEntities: entities => ({type: types.ADD_ENTITIES, entities}),
    updateEntity: (name, key, entity) => ({type: types.UPDATE_ENTITY, name, key, entity}),
    removeEntity: (name, key) => ({type: types.REMOVE_ENTITY, name, key}),
    addCoupledStatisticToTip: (coupledStatisticId, tipId) => ({
        type: types.ADD_COUPLED_STATISTIC_TO_TIP,
        coupledStatisticId,
        tipId
    }),
    addMomentToTip: (momentId, tipId)=> ({
        type: types.ADD_MOMENT_TO_TIP,
        momentId, tipId
    }),
    decoupleStatisticFromTip: (coupledStatistic) => ({type: types.DECOUPLE_STATISTIC_FROM_TIP, coupledStatistic}),
    decoupleMomentFromTip: (id, tipId) => ({type: types.DECOUPLE_MOMENT_FROM_TIP, id, tipId}),
    removeTip: id => ({type: types.REMOVE_TIP, id}),
    removeStatistic: id => ({type: types.STATISTIC_DELETED, id}),
};

const defaultState = {
    tips: {},
    coupledStatistics: {},
    cohorts: {},
    statistics: {},
    statisticVariables: {},
    educationProgramTypes: {},
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

        case types.REMOVE_ENTITY: {
            const entities = {...state[action.name]};
            delete entities[action.key];
            return {...state, [action.name]: entities};
        }

        case types.ADD_MOMENT_TO_TIP: {
            const tip = {...state.tips[action.tipId]};
            tip.moments = [...tip.moments, action.momentId];

            return {
                ...state, tips: {...state.tips, [tip.id]: tip}
            };
        }

        case types.ADD_COUPLED_STATISTIC_TO_TIP: {
            const tip = {...state.tips[action.tipId]};
            tip.coupled_statistics = [...tip.coupled_statistics, action.coupledStatisticId];
            return {
                ...state,
                tips: {...state.tips, [tip.id]: tip}
            };
        }

        case types.DECOUPLE_STATISTIC_FROM_TIP: {
            const tip = {...state.tips[action.coupledStatistic.tip_id]};
            tip.coupled_statistics = [...tip.coupled_statistics];
            tip.coupled_statistics.splice(tip.coupled_statistics.indexOf(action.coupledStatistic.id), 1);

            const coupledStatistics = {...state.coupledStatistics};
            delete coupledStatistics[action.coupledStatistic.id];

            return {
                ...state,
                tips: {...state.tips, [tip.id]: tip},
                coupledStatistics
            }
        }

        case types.DECOUPLE_MOMENT_FROM_TIP: {
            console.log(action);
            const tip = {...state.tips[action.tipId]};
            console.log(tip);
            tip.moments.splice(tip.moments.indexOf(action.id), 1);

            return {
                ...state,
                tips: {...state.tips, [tip.id]: tip},
            }
        }

        case types.REMOVE_TIP: {
            const tips = {...state.tips};
            delete tips[action.id];
            return {...state, tips};
        }

        case types.STATISTIC_DELETED: {
            const statistics = {...state.statistics};
            delete statistics[action.id];

            let coupledIds = []; // Collect the ids of coupledStatistics that used this statistic, they need to be removed from tips and from entitycollection
            const coupledStatistics = Object.values(state.coupledStatistics).filter(coupledStatistic => {
                if (coupledStatistic.statistic === action.id) {
                    coupledIds.push(coupledStatistic.id);
                }
                return coupledStatistic.statistic !== action.id
            }).reduce((carry, coupledStatistic) => {
                carry[coupledStatistic.id] = coupledStatistic;
                return carry;
            }, {});

            const tips = [...Object.values(state.tips)].map(tip => {
                const newTip = {...tip};
                newTip.coupled_statistics = newTip.coupled_statistics.filter(id => !coupledIds.includes(id));
                return newTip;
            }).reduce((carry, tip) => {
                carry[tip.id] = tip;
                return carry;
            }, {});

            return {...state, statistics, coupledStatistics, tips};
        }

    }

    return state;
};


export {
    types, actions, reducer
}