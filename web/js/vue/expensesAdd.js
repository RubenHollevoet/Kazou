var app = new Vue({
    el: '#app',
    data: {
        page: 0,
        groupStack: [
            { id: 0, text: 'Vegetables' },
            { id: 1, text: 'Cheese' },
            { id: 2, text: 'Whatever else humans are supposed to eat' }
        ],
        activeGroups: [
            { id: 3, text: '--3 group'},
            { id: 5, text: '--4 group'}
        ]
    },
    methods: {
        prevStep: function () {
            this.page--
        },
        nextStep: function () {
            this.page++
        },
        submit: function () {
            console.log('todo: submit onkosten')
        },
        fetchGroups: function () {
            console.log('fetch new groups')
        }
    }
})

Vue.component('groupstack-item', {
    props: ['group'],
    template: '<li>{{ group.text }}</li>'
})

Vue.component('groupsavaliable-item', {
    props: ['group'],
    template: '<button v-on:click="fetchGroups" >{{ group.text }}</button>'
})
