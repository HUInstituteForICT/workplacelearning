import _ from "lodash";

export default class ProducingActivityProcessExporter {

    constructor(type, activities) {
        this.type = type;
        this.activities = activities;

        this.outputData = '';

        this[type]();
        this.download();
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
        this.output(translatedHeaders.join(",") + "\n");

        this.activities.forEach((activity, index) => {
            let values = headers.map(header => {
                if (unwantedColumns.indexOf(header) !== -1) return;
                return activity[header];
            });
            let dataString = values.join(",");
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
                if(header === 'description') {
                    return _.capitalize(exportTranslatedFieldMapping[header]) + ": \n\t" + activity[header] + " \n";
                }
                return _.capitalize(exportTranslatedFieldMapping[header]) + ": " + activity[header];
            });
            let dataString = lines.join("\n");
            this.output(index < this.activities.length ? dataString + "\n______________\n\n" : dataString);
        });
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

}