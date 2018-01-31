import {schema} from "normalizr";


const tip = new schema.Entity('tips');
const cohort = new schema.Entity('cohorts');
const coupledStatisticSchema = new schema.Entity('coupledStatistics');
const statistic = new schema.Entity('statistics');
const statisticVariable = new schema.Entity('statisticVariables');
const availableStatisticVariable = new schema.Entity('availableStatisticVariables');
const educationProgramType = new schema.Entity('educationProgramTypes', {}, {idAttribute: 'eptype_id'});
const availableStatistic = new schema.Entity('availableStatistics');

tip.define({enabled_cohorts: [cohort], coupled_statistics: [coupledStatisticSchema]});

statistic.define({
    education_program_type: educationProgramType,
    statistic_variable_one: statisticVariable,
    statistic_variable_two: statisticVariable
});
availableStatistic.define({
    education_program_type: educationProgramType,
    statistic_variable_one: statisticVariable,
    statistic_variable_two: statisticVariable
});
coupledStatisticSchema.define({statistic});


const tips = new schema.Array(tip);
const cohorts = new schema.Array(cohort);
const statistics = new schema.Array(statistic);
const availableStatisticVariables = new schema.Array(availableStatisticVariable);
const availableStatistics = new schema.Array(availableStatistic);
const educationProgramTypes = new schema.Array(educationProgramType);

const loadSchema = {tips, cohorts, statistics, educationProgramTypes, availableStatisticVariables, availableStatistics};


export {
    tip, cohort, coupledStatisticSchema, statisticVariable, availableStatisticVariable, educationProgramType,
    tips, cohorts, statistics, availableStatisticVariables, educationProgramTypes, availableStatistics,
    availableStatistic,

    loadSchema
}