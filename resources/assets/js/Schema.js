import {schema} from "normalizr";


const tip = new schema.Entity('tips');
const cohort = new schema.Entity('cohorts');
const coupledStatistic = new schema.Entity('coupledStatistics');
const statistic = new schema.Entity('statistics');
const statisticVariable = new schema.Entity('statisticVariables');
const educationProgramType = new schema.Entity('educationProgramTypes', {}, {idAttribute: 'eptype_id'});
const educationProgram = new schema.Entity('educationPrograms', {}, {idAttribute: 'ep_id'});
const moment = new schema.Entity('moments');


tip.define({enabled_cohorts: [cohort], coupled_statistics: [coupledStatistic], moments: [moment]});

statistic.define({
    statistic_variable_one: statisticVariable,
    statistic_variable_two: statisticVariable
});
statisticVariable.define({
});
coupledStatistic.define({statistic});


const tips = new schema.Array(tip);
const cohorts = new schema.Array(cohort);
const statistics = new schema.Array(statistic);
const statisticVariables = new schema.Array(statisticVariable);
const educationProgramTypes = new schema.Array(educationProgramType);
const educationPrograms = new schema.Array(educationProgram);

const loadSchema = {tips, cohorts, statistics, statisticVariables, educationProgramTypes, educationPrograms};


export const Schema = {
    tip, cohort, coupledStatistic, statisticVariable, educationProgramType,
    tips, cohorts, statistics, educationProgramTypes,
    statistic, moment,

    loadSchema
};