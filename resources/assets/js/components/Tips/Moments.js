import React from "react";
import {connect} from "react-redux";
import {actions as entityActions} from "./redux/entities";
import CoupledStatisticItem from "./CoupledStatisticItem";
import MomentItem from "./MomentItem";
import CreateForm from "../Statistics/CreateForm";
import {normalize} from "normalizr";
import {Schema} from "../../Schema";
import Modal from "react-responsive-modal";
import axios from "axios";


class Moments extends React.Component {

    constructor(props) {
        super(props);
        this.state = {
            showNewMomentModal: false,
            newRangeStart: 0,
            newRangeEnd: 20,
        }
    }

    createMoment = () => {
        const moment = {rangeStart: this.state.newRangeStart, rangeEnd: this.state.newRangeEnd};
        axios.post(`/api/moments/create/${this.props.tip.id}`, moment).then(response => {
            this.props.storeNewMoment(normalize(response.data, Schema.moment));
            // if (this.props.runJoyride) {
            //     this.props.joyrideRef.helpers.next();
            // }
            this.setState({showNewMomentModal: false});
        })
    };

    render() {

        const {tip} = this.props;
        return <div>
            <h3>{Lang.get('tips.moment-trigger')}</h3>

            <p>{Lang.get('tips.moment-trigger-detail')}</p>

            <button className="btn btn-primary"
                    onClick={() => this.setState({showNewMomentModal: true})}>{Lang.get('tips.couple-moment')}
            </button>

            <br/><br/>


            <div className="row">
                {
                    tip.moments.map(id => <div className="col-md-4" key={id}><MomentItem id={id}/></div>)
                }
            </div>

            <Modal open={this.state.showNewMomentModal} little
                   onClose={() => this.setState({showNewMomentModal: false})}
                   classNames={{'modal': "panel panel-default"}}>
                <div className="panel-body" id="step-8">
                    <h3>{Lang.get('tips.new-moment')}</h3>

                    <div className="row" id="step-moment-2">

                        <div className="col-lg-3">
                            <div className="form-group">
                                <label>{Lang.get('tips.rangeStart')}</label>
                                <div className="input-group">
                                    <input min="0" max="100" value={this.state.newRangeStart} type="number" step={1}
                                           className="form-control" onChange={e => this.setState({newRangeStart: e.target.value})}/>
                                    <span className="input-group-addon">%</span>
                                </div>
                            </div>
                        </div>

                        <div className="col-lg-3">
                            <div className="form-group">
                                <label>{Lang.get('tips.rangeEnd')}</label>
                                <div className="input-group">
                                    <input min="0" max="100" value={this.state.newRangeEnd} type="number" step={1}
                                           className="form-control" onChange={e => this.setState({newRangeEnd: e.target.value})}/>
                                    <span className="input-group-addon">%</span>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div className="row">
                        <div className="col-lg-3 pull-right">
                            <button onClick={this.createMoment} className="btn btn-primary">
                                {Lang.get('tips.create')}
                            </button>
                        </div>
                    </div>

                </div>
            </Modal>
        </div>
    }
}

const mapping = {
    state: state => {
        return {};
    },
    dispatch: dispatch => {
        return {
            storeNewMoment: normalized => {
                dispatch(entityActions.addEntities(normalized.entities));
                dispatch(entityActions.addMomentToTip(normalized.result, normalized.entities.moments[normalized.result].tip_id));
            },
            updateEntity: (name, key, entity) => dispatch(entityActions.updateEntity(name, key, entity)),
        }
    }
};

export default connect(mapping.state, mapping.dispatch)(Moments);