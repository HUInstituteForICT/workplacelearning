import React from "react";

export default class FilterForm extends React.Component {

    constructor(props) {
        super(props);

        this.state = {
            form: this.buildFilter(this.props.activities),
            filter: {
                dateStart: [],
                dateEnd: [],
                timeslot: [],
                resourcePerson: [],
                resourceMaterial: [],
                learningGoal: [],
                competence: []
            }
        }
    }

    buildFilter(activities) {
        let form = {
            timeslot: {label: "Tijdslot", options: [], selected: null},
            learningGoal: {label: "Leervraag", options: [], selected: null},
            competence: {label: "Competentie", options: [], selected: null},
        };

        activities.map((activity) => {

            form.timeslot.options.indexOf(activity.timeslot) === -1 ? form.timeslot.options.push(activity.timeslot) : '';
            form.learningGoal.options.indexOf(activity.learningGoal) === -1 ? form.learningGoal.options.push(activity.learningGoal) : '';
            form.competence.options.indexOf(activity.competence) === -1 ? form.competence.options.push(activity.competence) : '';
        });

        return form;
    }

    onChange(event, field) {
        console.log(event.target.value, field);
        let newState = this.state.filter[field];

        this.setState();
    }

    render() {
        let fields = ["timeslot", "learningGoal", "competence"];
        return <div>

            {fields.map((fieldName) => {
                let field = this.state.filter[fieldName];
                return <div key={fieldName}>
                    <label>{field.label}
                        <select multiple="multiple" onChange={(e) => {this.onChange(e, fieldName)}}>
                            {field.options.map((entry) => {
                                return <option key={entry} value={entry}>{entry}</option>;
                            }) }
                        </select>
                    </label>
                </div>;
            })}

        </div>;
    }

}