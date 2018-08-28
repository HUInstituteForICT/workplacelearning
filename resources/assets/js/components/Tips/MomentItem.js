import React from "react";
import {connect} from "react-redux";
import {actions as entityActions} from "./redux/entities";
import axios from "axios";


class MomentItem extends React.Component {

    constructor(props) {
        super(props);
        this.state = {
            editMode: false,
            newRangeStart: props.moment.rangeStart,
            newRangeEnd: props.moment.rangeEnd,
        }
    }

    decoupleMoment = () => {
        axios.delete(`/api/moments/${this.props.moment.id}`).then(response => {
            const id = this.props.moment.id;
            const tipId = this.props.moment.tip_id;
            this.props.decoupleMomentFromTip(id, tipId);
            this.props.removeEntity('moments', id);
        });
    };

    saveMoment = () => {
        const momentData = {rangeStart: this.state.newRangeStart, rangeEnd: this.state.newRangeEnd};
        axios.put(`/api/moments/${this.props.moment.id}`, momentData).then(response => {
            this.props.updateEntity('moments', this.props.moment.id, {...this.props.moment, ...momentData});
            this.setState({editMode: false});
        });
    };

    render() {
        const {moment} = this.props;

        return <div className="panel panel-default" style={{flex: '1'}}>
            <div className="panel-body">
                <div>
                    {!this.state.editMode &&
                    <h4>
                        {Lang.get('tips.percentage-period', {percentage: `${moment.rangeStart}% - ${moment.rangeEnd}%`})}
                    </h4>
                    }

                    {this.state.editMode && <div>
                        <div className="form-group">
                            <label>{Lang.get('tips.rangeStart')}</label>
                            <div className="input-group">
                                <input min="0" max="100" value={this.state.newRangeStart} type="number" step={1}
                                       className="form-control"
                                       onChange={e => this.setState({newRangeStart: e.target.value})}/>
                                <span className="input-group-addon">%</span>
                            </div>
                        </div>

                        <div className="form-group">
                            <label>{Lang.get('tips.rangeEnd')}</label>
                            <div className="input-group">
                                <input min="0" max="100" value={this.state.newRangeEnd} type="number" step={1}
                                       className="form-control"
                                       onChange={e => this.setState({newRangeEnd: e.target.value})}/>
                                <span className="input-group-addon">%</span>
                            </div>
                        </div>

                    </div>}


                </div>
                <br/>

                {!this.state.editMode && <div style={{display: 'flex', flexDirection: 'row'}}>
                    <button onClick={() => this.setState({editMode: true})}
                            className="btn btn-primary">{Lang.get('general.edit')}</button>
                </div>}

                {this.state.editMode && <div style={{display: 'flex', flexDirection: 'row'}}>
                    <button onClick={this.saveMoment} className="btn btn-primary">{Lang.get('tips.save')}</button>
                    &nbsp;
                    <button onClick={this.decoupleMoment}
                            className="btn btn-danger">{Lang.get('tips.decouple')}</button>
                    &nbsp;
                    <button onClick={() => this.setState({editMode: false})}
                            className="btn">{Lang.get('tips.cancel')}</button>
                </div>}
            </div>
        </div>;
    }
}

const mapping = {
    state: (state, props) => {
        return {
            moment: state.entities.moments[props.id],
        }
    },
    dispatch: dispatch => {
        return {
            updateEntity: (name, key, entity) => dispatch(entityActions.updateEntity(name, key, entity)),
            removeEntity: (name, key) => dispatch(entityActions.removeEntity(name, key)),
            decoupleMomentFromTip: (id, tipId) => dispatch(entityActions.decoupleMomentFromTip(id, tipId))
        }
    }
};

export default connect(mapping.state, mapping.dispatch)(MomentItem);