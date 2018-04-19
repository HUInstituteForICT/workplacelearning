import {schema} from "normalizr";


const tip = new schema.Entity('tips');
const cohort = new schema.Entity('cohorts');
const coupledStatistic = new schema.Entity('coupledStatistics');
const statistic = new schema.Entity('statistics');
const statisticVariable = new schema.Entity('statisticVariables');
const educationProgramType = new schema.Entity('educationProgramTypes', {}, {idAttribute: 'eptype_id'});

tip.define({enabled_cohorts: [cohort], coupled_statistics: [coupledStatistic]});

statistic.define({
    education_program_type: educationProgramType,
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

const loadSchema = {tips, cohorts, statistics, statisticVariables, educationProgramTypes};


export const Schema = {
    tip, cohort, coupledStatistic, statisticVariable, educationProgramType,
    tips, cohorts, statistics, educationProgramTypes,
    statistic,

    loadSchema
};