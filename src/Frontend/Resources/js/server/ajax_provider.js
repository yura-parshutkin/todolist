import $ from 'jquery';

/**
 * прослойка для взаимодействием с серверной частью,
 * тут мы имеем право реализовать ajax или websocket
 */
export default class RestProvider {
    constructor(url) {
        this.url = url;
    }

    fetch(callback) {
        $.getJSON(this.url, function(response) {
            callback(response);
        });
    }

    add(item, callback) {
        const self = this;

        $.ajax({
            dataType: 'json',
            type: 'post',
            data: item,
            url: self.url,
            success(response){
                callback(response);
            }
        });
    }

    update(item, callback) {
        const self = this;

        $.ajax({
            dataType: 'json',
            type: 'put',
            data: item,
            url: self.url + '/' + item.id,
            success(response){
                callback(response);
            }
        });
    }

    remove(item, callback) {
        const self = this;

        $.ajax({
            dataType: 'json',
            type: 'delete',
            url: self.url + '/' + item.id,
            success(response){
                callback(response);
            }
        });
    }

    removeComplete(callback) {
        const self = this;

        $.ajax({
            dataType: 'json',
            type: 'patch',
            url: self.url + '/remove-complete',
            success(response){
                callback(response);
            }
        });
    }

    completeAll(callback) {
        const self = this;

        $.ajax({
            dataType: 'json',
            type: 'patch',
            url: self.url + '/complete',
            success(response){
                callback(response);
            }
        });
    }

    unCompleteAll(callback) {
        const self = this;

        $.ajax({
            dataType: 'json',
            type: 'patch',
            url: self.url + '/un-complete',
            success(response){
                callback(response);
            }
        });
    }
}