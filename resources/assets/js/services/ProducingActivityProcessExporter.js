import _ from "lodash";
import * as axios from "axios";

export default class ProducingActivityProcessExporter {

    constructor(type, includeFeedback, activities) {
        this.type = type;
        this.includeFeedback = includeFeedback;
        this.activities = activities;

        this.outputData = '';

    }

    getFeedbackUrl(id) {
        return "https://" + window.location.hostname + '/producing/feedback/' + id;
    }

    csv() {

        // Build headers and filter unwanted
        let headers = Object.keys(this.activities[0]);

        let unwantedColumns = ["id", "url", "difficultyValue", "hours"];
        unwantedColumns.forEach(column => {
            headers.splice(headers.indexOf(column), 1)
        });

        let translatedHeaders = headers.map(header => {
            return exportTranslatedFieldMapping[header]
        });
        this.output(translatedHeaders.join(";") + "\n");

        this.activities.forEach((activity, index) => {
            let values = headers.map(header => {
                if (unwantedColumns.indexOf(header) !== -1) return;
                if (activity[header] === null || activity[header] === 'null') {
                    return '';
                }
                if (header === 'feedback') {
                    return this.getFeedbackUrl(activity[header]['fb_id']);
                }
                return activity[header];
            }).map(this.escapeCsv);
            let dataString = values.join(";");
            this.output(index < this.activities.length ? dataString + "\n" : dataString);

        });
    }

    txt() {
        // Build headers and filter unwanted
        let headers = Object.keys(this.activities[0]);

        let unwantedColumns = ["id", "url", "difficultyValue", "hours"];
        unwantedColumns.forEach(column => {
            headers.splice(headers.indexOf(column), 1)
        });

        this.activities.forEach((activity, index) => {
            let lines = headers.map(header => {
                if (unwantedColumns.indexOf(header) !== -1) return;
                if (header === 'description') {
                    return _.capitalize(exportTranslatedFieldMapping[header]) + ": \n\t" + activity[header] + " \n";
                }
                if (activity[header] === null || activity[header] === 'null') {
                    return _.capitalize(exportTranslatedFieldMapping[header]) + ": -";
                }
                if (header === 'feedback') {
                    if (this.includeFeedback) {
                        return _.capitalize(exportTranslatedFieldMapping[header]) + ": \n\t" +
                            `${Lang.get('activity.feedback.why-hard')}: ` + (activity[header]['notfinished'] ? `${activity[header]['notfinished']}.` : "-") + "\n\t" +
                            `${Lang.get('activity.feedback.how-help')}: ` + (activity[header]['support_requested'] ? `${activity[header]['supported_provided_wp']}.` : "Nee.") + "\n\t" +
                            `${Lang.get('activity.feedback.happy-with-progress')}: ` + (activity[header]['progress_satisfied'] === 2 ? 'Ja.' : "Nee.") + "\n\t" +
                            `${Lang.get('activity.feedback.own-initiative')}: ` + (activity[header]['initiative'] ? `\n\t\t${activity[header]['initiative']}.` : "-") + "\n\t" +
                            `${Lang.get('activity.feedback.next-steps')}: ` + (activity[header]['nextstep_self'] ? `\n\t\t${activity[header]['nextstep_self']}.` : "-") + "\n\t" +
                            `${Lang.get('activity.feedback.help-needed')}: ` + (activity[header]['support_needed_wp'] ? `\n\t\t${activity[header]['support_needed_wp']}.` : "-") + "\n\t" +
                            `${Lang.get('activity.feedback.help-school-needed')}: ` + (activity[header]['support_needed_ed'] ? `\n\t\t${activity[header]['support_needed_ed']}.` : "-") + "\n\t" +
                            "Url: " + this.getFeedbackUrl(activity[header]['fb_id']);
                    } else {
                        return _.capitalize(exportTranslatedFieldMapping[header]) + ": " + this.getFeedbackUrl(activity[header]['fb_id']);
                    }
                }
                return _.capitalize(exportTranslatedFieldMapping[header]) + ": " + activity[header];
            });
            let dataString = lines.join("\n");
            this.output(index < this.activities.length ? dataString + "\n______________\n\n" : dataString);
        });
    }

    mail(email, comment, callback) {
        this.txt();

        axios.post('/activity-export-mail', {txt: this.outputData, email, comment})
            .then(callback)
            .catch(callback);
    }

    output(str) {
        this.outputData += str;
    }

    download() {
        let a = document.createElement('a');
        a.href = 'data:attachment/' + this.type + ',' + encodeURIComponent(this.outputData);
        a.target = '_blank';
        a.download = 'export.' + this.type;
        document.body.appendChild(a);
        a.click();
    }

    /**
     * @param string {string}
     */
    escapeCsv(string) {
        return '"' + string.replace(/"/g, '""') + '"';
    }

}