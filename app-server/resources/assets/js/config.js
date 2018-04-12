const apiDomain = Modian.apiDomain;

export const mokerRegister = apiDomain + '/moker.json';
export const mokerRegions = apiDomain + '/regions.json?parent_id={id}';
//export const mokerSms = apiDomain + '/send_message.json'
export const mokerSms = apiDomain + '/send_reg_message.json'
export const mokerInfo = apiDomain + '/moker/{id}.json'