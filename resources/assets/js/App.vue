<template>
    <div>
        <div class="vue-csv-uploader">
            <div class="form">
                <div class="form-check">
                    <input :class="checkboxClass" type="checkbox" id="hasHeaders" v-model="hasHeaders">
                    <label class="form-check-label" for="hasHeaders">
                        Has Headers
                    </label>
                </div>
                <div class="form-group">
                    <input ref="csv" type="file" :class="inputClass" name="csv">
                </div>
                <div class="form-group">
                    <input type="submit" :class="buttonClass" @click.prevent="getSample">
                </div>
                <div v-if = "csv">
                    <p>{{csv[0]}}</p>
                    <p>{{csv[1]}}</p>    
                    <p>{{csv[2]}}</p>
                    <p>{{csv[3]}}</p>
                </div>

                <div class="vue-csv-mapping" v-if="sample">
                    <table :class="tableClass">
                        <thead>
                        <tr>
                            <th v-for="(field) in mapFields">
                                {{ field }}
                            </th>
                        </tr>
                        <tr>
                            <th v-for="(field) in mapFields">
                                <select class="form-control" v-model="map[field]">
                                    <option v-for="(column, key) in firstRow" :value="key">{{ column }}</option>
                                </select>
                            </th>
                        </tr>
                        </thead>
                        <tbody v-if="form.csv">
                            <tr v-for="(item) in form.csv">
                                <td v-for ="(field) in mapFields">
                                    {{item[field]}}
                                </td>
                            </tr>
                        </tbody>
                        <tbody v-if="!form.csv && initial_data != null">
                            <tr v-for="(item) in initial_data">
                                <td v-for ="(field) in mapFields">
                                    {{item[field]}}
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="form-group" v-if="url">
                        <input type="submit" :class="buttonClass" @click.prevent="submit">
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import _ from "lodash";
    import axios from 'axios';

    const parse = require('csv-parse');

    export default {
        props: {
            value: Array,
            url: {
                type: String
            },
            mapFields: {
                type: Array,
                required: true
            },
            callback: {
                type: Function,
                default: (response) => {
                }
            },
            catch: {
                type: Function,
                default: (response) => {
                }
            },
            finally: {
                type: Function,
                default: (response) => {
                }
            },
            tableClass: {
                type: String,
                default: "table"
            },
            checkboxClass: {
                type: String,
                default: "form-check-input"
            },
            buttonClass: {
                type: String,
                default: "btn btn-default"
            },
            inputClass: {
                type: String,
                default: "form-control"
            }
        },

        data: () => ({
            form: {
                csv: null,
            },
            map: {},
            hasHeaders: true,
            csv: null,
            sample: null,
            initial_data: null,
        }),

        methods: {
            submit() {
                const _this = this;
                this.form.csv = this.buildMappedCsv();

                this.$emit('input', this.form.csv);

                if (this.url) {
                    axios.post(this.url, this.form).then(response => {
                        _this.callback(response);
                    }).catch(response => {
                        _this.catch(response);
                    }).finally(response => {
                        _this.finally(response);
                    });
                }
            },
            buildMappedCsv() {
                const _this = this;

                let csv = this.hasHeaders ? _.drop(this.csv) : this.csv;

                return _.map(csv, (row) => {
                    let newRow = {};

                    _.forEach(_this.map, (column, field) => {
                        _.set(newRow, field, _.get(row, column));
                    });

                    return newRow;
                });
            },
            getSample() {
                const _this = this;

                this.readFile((output) => {
                    parse(output, {to: 2}, function (err, output) {
                        _this.sample = output;
                    });
                    parse(output, {}, function (err, output) {
                        _this.csv = output;
                    });
                });
                axios.get(this.url+'/1').then(response => {
                    _this.initial_data = response.initial_data;
                });
                console.log(_this.initial_data);
            },
            readFile(callback) {
                let file = this.$refs.csv.files[0];

                if (file) {
                    let reader = new FileReader();

                    reader.readAsText(file, "UTF-8");

                    reader.onload = function (evt) {
                        callback(evt.target.result);
                    };

                    reader.onerror = function (evt) {
                        console.log("Error reading CSV");
                    };
                }
            }
        },

        watch: {
            map: {
                handler: function (newVal) {
                    if(!this.url){
                        var hasAllKeys = this.mapFields.every(function(item){
                            return newVal.hasOwnProperty(item);
                        });

                        if (hasAllKeys) {
                            this.submit();
                        }
                    }
                },
                deep: true
            }
        },

        computed: {
            firstRow() {
                return _.get(this, "sample.0");
            },
            headers() {
                return _.get(this, "sample.0");
            }
        },
    };
</script>