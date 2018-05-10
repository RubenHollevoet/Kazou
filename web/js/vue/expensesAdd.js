Vue.component('form-errors', {
    props: ['error'],
    template: '<li>{{error}}</li>'
});

Vue.component('groupstack-item', {
    props: ['group'],
    template: '<li v-bind:data-id="group.id"><span class="btn btn-danger fa fa-remove" v-on:click="revertSelectors"></span> {{ group.text }}</li>',
    methods: {
        revertSelectors: function (evt) {
            var newArrayLength = $(evt.currentTarget.parentElement).index();
            this.$root.groupStack = this.$root.groupStack.slice(0, newArrayLength);

            var prevParentId = this.$root.groupStack[newArrayLength - 1] ? this.$root.groupStack[newArrayLength - 1].id : 0;
            this.$root.fetchGroups(prevParentId);
        }
    }
});

Vue.component('groupsavaliable-item', {
    props: ['group'],
    template: '<span class="btn btn-default" v-bind:data-id="group.id" v-on:click="loadNewGroups">{{ group.name }}</span>',
    methods: {
        loadNewGroups: function (evt) {
            this.$root.groupStack.push({id: this.group.id + '-' + this.group.type, text: this.group.name});
            console.log(this.group);
            if (this.group.type !== 'activity') {

                this.$root.fetchGroups(evt.currentTarget.dataset.id);
                this.$root.tripData.activityId = this.group.id;
            }
            else {
                this.$root.tripData.groupId = this.group.id;
            }

            //clear the shown active groups until the new ones are loaded
            this.$root.activeGroups = [];
        },
    }
});

var app = new Vue({
    el: '#expenses_app',
    data: {
        counter: '',
        page: 0,
        formErrors: [],
        groupStack: [],
        activeGroups: [],
        userDataSet: false,
        userData: {
            name: '',
            email: '',
            iban: '',
            personId: '',
        },
        tripData: {
            from: '',
            to: '',
            date: '',
            transportType: '',
            company: '',
        },
        submitStatus: 0,
    },
    computed: {
        submitStatusClass: function (transportType) {
            return {
                'fa fa-cog fa-spin fa-fw': this.submitStatus === 0,
                'fa fa-check': this.submitStatus === 200,
                'fa fa-exclamation-triangle': this.submitStatus === 500,
            }
        },
    },
    methods: {
        prevStep: function () {
            if (this.validatePage(this.page)) this.page--;
        },
        nextStep: function () {
            if (this.validatePage(this.page)) this.page++;
        },
        submit: function () {
            var self = this;
            this.page++;
            self.submitStatus = 0;

            var tripData = {
                userData: this.userData,
                tripData: this.tripData
            };
            axios.post('/expenses/api/createTrip', tripData)
                .then(function (response) {
                    self.submitStatus = 200;
                    console.log(response);
                })
                .catch(function (error) {
                    self.submitStatus = 500;
                    console.log(error);
                });
        },
        validatePage: function (pageId) {
            this.formErrors = [];

            if (pageId === 1) {
                if (!this.userData.name.length) this.formErrors.push('vul je naam in');
                else if (this.userData.name.trim().indexOf(' ') < 0) this.formErrors.push('schrijf je voor- en achternaam gescheiden door een spatie');
                if (!this.userData.email.length) this.formErrors.push('vul je email in');
                else if (this.userData.email.indexOf('@') < 0 || this.userData.email.split('@')[1].indexOf('.') < 0) this.formErrors.push('het opgegeven emailadres is ongeldig');
                if (!this.userData.iban.length) this.formErrors.push('vul je iban in');
                if (!this.userData.personId.length) this.formErrors.push('vul je rijksregisternummer in');
            }
            else if (pageId === 2) {
                if (this.activeGroups.length) this.formErrors.push('specifieer je activiteit. Kies tussen de aangegeven activiteiten');
                if (!this.tripData.date) this.formErrors.push('vul de datum in waarop de activiteit plaats vond');
                if (!this.tripData.to) this.formErrors.push('vul de plaats van de activiteit in');
            }
            console.log('todo: validate page ' + pageId);
            return !this.formErrors.length > 0;
        },
        fetchGroups: function (id = 0) {
            var self = this;
            axios.get('/expenses/api/getChildGroups?group=' + id.toString())
            //axios.get('//kazourmt.dev.be/app_dev.php/expenses/api/getChildGroups?group=' + id.toString())
                .then(function (response) {
                    self.activeGroups = response.data.data;
                    self.formErrors = [];
                    if (response.data.data.length < 1) self.fetchActivity(self.groupStack.slice(-1)[0].id)
                })
                .catch(function (error) {
                    self.fetchError = error;
                })
        },
        fetchActivity: function (id) {
            var self = this;
            axios.get('/expenses/api/getTripActivities?group=' + id.toString())
            //axios.get('//kazourmt.dev.be/app_dev.php/expenses/api/getChildGroups?group=' + id.toString())
                .then(function (response) {
                    // console.log(response.data.status);
                    if (response.data.status === 'ok') {
                        self.activeGroups = response.data.data;
                        self.formErrors = [];
                    }
                    else {
                        self.formErrors.push('De huidige groep bezit geen activiteiten. Contacteer het Kazou team.');
                        console.log('error when requesting activities', response.data);
                    }
                })
                .catch(function (error) {
                    self.fetchError = error;
                    console.log('error 123', error);
                })
        },
        setUserData(userData) {
            if (!this.userDataSet) {
                this.userData = Object.assign({}, this.userData, userData);
                this.userDataSet = true;
            }
        },
    },
    mounted: function () {
        this.fetchGroups(0);
    }
});
