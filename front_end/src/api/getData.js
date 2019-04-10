import fetch from '../config/fetch'

/**
 * 登录
 */

export const login = data => fetch('/Login/login', 'post', data);

/**
 * 检查是否登录
 */

export const checkLogin = () => fetch('/Login/checkLogin', 'get');