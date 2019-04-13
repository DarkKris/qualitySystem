import fetch from '../config/fetch'

/**
 * 登录
 */

export const login = data => fetch('/Login/login', 'post', data);

/**
 * 检查是否登录
 */

export const checkLogin = () => fetch('/Login/checkLogin', 'get');

/**
 * 注销用户
 */

export const logout = () => fetch('/Login/logout', 'get');

/**
 * 获取case聊天记录
 */

export const getCaseMsg = data => fetch('/Case/getCaseMsg', 'post', data);

/**
 * 获取case信息
 */

export const getCaseInfo = data => fetch('/Case/getCaseInfo', 'post', data);

/**
 * 获取case成绩信息
 */

export const getCaseGrade = data => fetch('/Case/getCaseGrade', 'post', data);