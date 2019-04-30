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

export const getCaseMsg = data => fetch('/CaseController/getCaseMsg', 'post', data);

/**
 * 获取case信息
 */

export const getCaseInfo = data => fetch('/CaseController/getCaseInfo', 'post', data);

/**
 * 获取case成绩信息
 * @param data
 * @returns {Promise<*>}
 */

export const getCaseGrade = data => fetch('/CaseController/getCaseGrade', 'post', data);

/**
 * 根据筛选条件获取case数
 * @param data
 * @returns {Promise<*>}
 */

export const getFilterCount = data => fetch('/CaseController/filterCount', 'post', data);

/**
 * 获取被质检团队列表
 * @returns {Promise<*>}
 */

export const getBeTestTeam = () => fetch('/CaseController/getBeTestTeam', 'get');

/**
 * 获取质检员列表
 * @returns {Promise<*>}
 */

export const getTestWorker = () => fetch('/CaseController/getTestWorker','get');

/**
 * 获取创建人列表
 * @returns {Promise<*>}
 */

export const getCreater = () => fetch('/CaseController/getCreater','get');

/**
 * 获取质检单列表
 * @param data
 * @returns {Promise<*>}
 */

export const getCaseData = data => fetch('/CaseController/getCaseData', 'post', data);