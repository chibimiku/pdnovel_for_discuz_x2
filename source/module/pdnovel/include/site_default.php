<?php
//----��������start
$pdnovel['utf8'] = '';
$pdnovel['cookie'] = '';
$pdnovel['subnovelid'] = 'floor(<{novelid}>/1000)'; //С˵��������㷽��
$pdnovel['subchapterid'] = ''; //С˵�½���������㷽��
//----��������end

//----С˵��Ϣҳ��ɼ�����start'
$pdnovel['url'] = ''; //С˵��Ϣҳ��ַ
$pdnovel['name'] = ''; //С˵���Ʋɼ�����
$pdnovel['author'] = ''; //С˵���߲ɼ�����
$pdnovel['cat'] = ''; //С˵����ɼ�����
$pdnovel['cover'] = ''; //С˵����ɼ�����
$pdnovel['coverkeycancel'] = ''; //���˵ķ���ͼƬ
$pdnovel['coverfilter'] = '';//�����ַ�
$pdnovel['coversite'] = '';//�����ַǰ׺
$pdnovel['keyword'] = ''; //С˵�ؼ��ֲɼ�����
$pdnovel['intro'] = ''; //С˵���ɼ�����
$pdnovel['notice'] = ''; //���߹���ɼ�����
$pdnovel['full'] = ''; //����״̬�ɼ�����
$pdnovel['fullnovel'] = ''; //�걾��־
//�����滻
$pdnovel['catid'][''] = '';
$pdnovel['permission'] = '0'; //С˵��Ȩ
$pdnovel['first'] = '0'; //С˵�׷�
//----С˵��Ϣҳ��ɼ�����end

//----С˵Ŀ¼ҳ��ɼ�����start
$pdnovel['chapterurl'] = ''; //С˵Ŀ¼ҳ��ַ
$pdnovel['lastchapter'] = ''; //����½�
$pdnovel['chapter'] = ''; //�½����Ʋɼ�����
$pdnovel['chapterid'] = ''; //�½�ID�ɼ�����
$pdnovel['volume'] = ''; //�����Ʋɼ�����
//----С˵Ŀ¼ҳ��ɼ�����end

//----�½�����ҳ��ɼ�����start
$pdnovel['readurl'] = ''; //�½��Ķ���ַ
$pdnovel['content'] = ''; 

//�½����ݲɼ�����
$pdnovel['replace'] = ''; //�½����ݹ���
$pdnovel['contentfilter'] = ''; //�½ڹ��������滻Ϊ
$pdnovel['pregreplace'] = ''; //�½������������
$pdnovel['pregcontentfilter'] = ''; //������������滻Ϊ
$pdnovel['addcontents'] =''; //�½��������
//----�½�����ҳ��ɼ�����end

//----ҳ���б��ʽstart
$pdnovel['pageurl'] = ''; //Ĭ���б�ҳ�ɼ���ַ
$pdnovel['page'] = ''; //�б�ҳ�ɼ�����

//----ҳ���б��ʽend

//----�ɼ��¼�start

//��Ϣҳ����ʼ
function viewstart( $source ){
	return $source;
}

//��Ϣҳ�������
function viewend( $source ){
	return $source;
}

//�½��б�ҳ����ʼ
function liststart( $source ){
	return $source;
}

//�Ķ�ҳ����ʼ
function readstart( $source ){
	return $source;
}

//׼������ͼƬ����
function imgstart( $source ){
	return $source;
}

//׼��д���½�����
function writestart( $source ){
	return $source;
}

//ҳ���б���ʼ
function pagestart( $source ){
	return $source;
}

//----�ɼ��¼�end
?>
