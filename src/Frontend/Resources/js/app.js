import './../../../../node_modules/todomvc-app-css/index.css';

import $ from 'jquery';
window.$ = $;
window.jQuery = $;

import AjaxProvider from './server/ajax_provider';
import TaskStore from './store/task_store';
import ToDoComponent from './component/to_do_component';

const id           = $('body').data('config')['todo'];
const url          = '/api/todo-list/' + id + '/tasks';
const taskProvider = new AjaxProvider(url);
const store        = new TaskStore(taskProvider);
const app          = new ToDoComponent('#todo', store);
