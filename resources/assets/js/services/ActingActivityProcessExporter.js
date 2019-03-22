import _ from "lodash";

export default class ActingActivityProcessExporter {

    constructor(type, activities) {
        this.type = type;
        this.activities = activities;

        this.outputData = '';

    }

    csv() {

        // Build headers and filter unwanted
        let headers = Object.keys(this.activities[0]);

        let unwantedColumns = ["id", "url"];
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
                if(Array.isArray(activity[header])) {
                    if (header === 'evidence') {
                        return activity[header].map(evidence => evidence.url).join(', ');
                    }
                    return activity[header].join(', ');
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

        let unwantedColumns = ["id", "url"];
        unwantedColumns.forEach(column => {
            headers.splice(headers.indexOf(column), 1)
        });

        this.activities.forEach((activity, index) => {
            let lines = headers.map(header => {
                if (unwantedColumns.indexOf(header) !== -1) return;
                if(header === 'situation' || header === 'lessonsLearned') {
                    return _.capitalize(exportTranslatedFieldMapping[header]) + ": \n\t" + activity[header] + " \n";
                } else if(header === 'competence') {
                    return _.capitalize(exportTranslatedFieldMapping[header]) + ": " + activity[header].join(', ')
                } else if(header === 'evidence') {
                    return _.capitalize(exportTranslatedFieldMapping[header]) +  ":\n" + activity[header].map(evidence => evidence.name + ": \t" + evidence.url).join("\n");
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