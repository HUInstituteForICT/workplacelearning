import * as React from "react";
import EducationProgramService from "../../services/EducationProgramService";

const locales = ["en", "nl"];

export default class EntityListEntry extends React.Component {

    constructor(props) {
        super(props);
        this.state = {
            loading: false,
            editMode: false,
            fieldValue: '',
            translations: {},
            loadingTranslations: false
        };
        this.toEdit = this.toEdit.bind(this);
        this.save = this.save.bind(this);
        this.onChangeFieldValue = this.onChangeFieldValue.bind(this);
        this.onKeyPress = this.onKeyPress.bind(this);
        this.loadTranslations = this.loadTranslations.bind(this);
        this.addTranslation = this.addTranslation.bind(this);
        this.setTranslation = this.setTranslation.bind(this);
        this.removeTranslation = this.removeTranslation.bind(this);
    }

    loadTranslations() {
        this.setState({loadingTranslations: true});
        EducationProgramService.loadTranslations(this.props.type, this.props.id, function (response) {
            this.setState({translations: response.data.translations, loadingTranslations: false});

        }.bind(this));
    }

    toEdit() {
        this.setState({
            editMode: true,
            fieldValue: this.props.label
        });
        this.loadTranslations();
    }

    save() {
        this.setState({loading: true});

        EducationProgramService.updateEntity(this.props.id, {
                type: this.props.type,
                name: this.state.fieldValue,
                translations: this.state.translations
            },
            response => {
                this.props.onEntityUpdatedName(this.props.id, this.props.type, this.state.fieldValue, response.data.mappedNameField);
                this.setState({loading: false, editMode: false});

            });

    }

    onChangeFieldValue(element) {
        this.setState({fieldValue: element.target.value});
    }

    onKeyPress(event) {
        if (event.key === 'Enter') {
            this.save();
        }
    }

    addTranslation(locale) {
        if (!locales.includes(locale)) return;
        if (this.state.translations.hasOwnProperty(locale)) return;

        let translations = {...this.state.translations};
        translations[locale] = "";

        this.setState({translations: translations});
    }

    removeTranslation(locale) {
        if (!locales.includes(locale)) return;

        if (!this.state.translations.hasOwnProperty(locale)) return;

        let translations = {...this.state.translations};
        delete translations[locale];

        this.setState({translations: translations});
    }

    setTranslation(locale, value) {
        this.setState({translations: {...this.state.translations, [locale]: value}});
    }

    render() {

        let styleBtn = {
            margin: '5px 0px',
            display: 'block'
        };

        // Render edit field
        if (this.state.editMode) {
            return <div className="buttonListItem expand">
                {this.state.loading && <div className="loader"/>}
                {!this.state.loading && <div className="">

                    <div className="input-group">
                        <input onKeyPress={this.onKeyPress} type="text" className="form-control"
                               maxLength="45"
                                value={this.state.fieldValue}
                               onChange={this.onChangeFieldValue}/>
                        <span className="input-group-addon">{this.state.fieldValue.length} / 45</span>
                    </div>

                    <hr/>
                    {this.state.loadingTranslations && <div className="loader">Loading...</div>}
                    {!this.state.loadingTranslations &&
                    <div>{this.renderTranslationButton()}{this.renderTranslations()}</div>}

                    <hr/>
                    <br/>
                    <div className="btn-group btn-group-justified">
                    <span className="btn btn-success" onClick={this.save}>
                        {Lang.get('react.save')}
                        </span>
                        <span className="btn btn-danger"
                              onClick={() => this.props.onRemoveClick(this.props.id, this.props.type)}>
                        {Lang.get('react.delete')}
                        </span>
                    </div>
                </div>}
            </div>
        }

        // Render the default show field
        return <div className="buttonListItem">
            <span className="defaultButton" onClick={this.toEdit}>
                {this.props.label}
            </span>
        </div>
    }


    renderTranslationButton() {
        if (locales.filter(locale => !this.state.translations.hasOwnProperty(locale)).length === 0) {
            return <div className="btn-group">
                <button disabled type="button" className="btn btn-default"
                        aria-expanded="false">{Lang.get('general.translations')}</button>
            </div>
        }


        return <div className="btn-group">
            <button type="button" className="btn btn-default dropdown-toggle" data-toggle="dropdown"
                    aria-expanded="false">{Lang.get('general.translations')}&nbsp;<span
                className="caret"></span>
            </button>
            <ul className="dropdown-menu">
                {locales.map(locale => {
                    if (this.state.translations.hasOwnProperty(locale)) return null;

                    return <li key={locale} onClick={() => this.addTranslation(locale)}>
                        <a>{Lang.get('general.' + locale)}</a></li>;

                })}
            </ul>
        </div>;
    }

    renderTranslations() {
        if (Object.keys(this.state.translations).length === 0) return null;
        return <div style={{marginTop: '10px'}}>
            {Object.keys(this.state.translations).map(locale =>
                <div className="input-group" key={locale} style={{marginTop: '10px'}}>
                    <span className="input-group-addon">{Lang.get('general.' + locale)}</span>
                    <input type="text" className="form-control" value={this.state.translations[locale]}
                           onChange={e => this.setTranslation(locale, e.target.value)}/>
                    <span className="input-group-btn">
                            <button className="btn btn-danger" type="button"
                                    onClick={() => this.removeTranslation(locale)}>
                                  <span className="glyphicon glyphicon-trash"/>
                            </button>
                        </span>
                </div>
            )}
        </div>;
    }


}