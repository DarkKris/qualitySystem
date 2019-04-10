import { baseUrl } from ''

export default async(url = '', data = {}, method = 'get' ) => {
    var res;
    // 地址拼接
    if(url.substr(0,1)==='/') {
        url = baseUrl + url.substr(1);
    }else {
        url = baseUrl + url;
    }
    // 信息请求
    axios({
        method: method,
        url: url,
        data: data
    }).then( response => (res = response));
    // 响应返回
    return res;
}