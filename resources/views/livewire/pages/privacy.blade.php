<?php

use function Livewire\Volt\layout;

layout('components.layouts.guest');

?>

<div class="min-h-screen bg-gray-50 dark:bg-zinc-900 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-zinc-800 rounded-2xl shadow-xl border border-gray-200 dark:border-zinc-700 p-8">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">개인정보처리방침</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">최종 수정일: 2025년 10월 25일</p>
            </div>

            <div class="prose prose-gray dark:prose-invert max-w-none space-y-8">
                <section>
                    <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                        (주)루펜드(이하 "회사")가 운영하는 해시푸드 서비스는 이용자의 개인정보를 중요시하며, 「개인정보 보호법」, 「정보통신망 이용촉진 및 정보보호 등에 관한 법률」 등 관련 법령을 준수하고 있습니다. 회사는 본 개인정보처리방침을 통하여 이용자가 제공하는 개인정보가 어떠한 용도와 방식으로 이용되고 있으며, 개인정보보호를 위해 어떠한 조치가 취해지고 있는지 알려드립니다.
                    </p>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">1. 개인정보의 수집 및 이용 목적</h2>
                    <div class="space-y-3 text-gray-700 dark:text-gray-300">
                        <p>회사는 수집한 개인정보를 다음의 목적을 위해 활용합니다:</p>
                        <ul class="list-disc list-inside ml-4 space-y-2">
                            <li><strong>회원 관리:</strong> 회원제 서비스 이용에 따른 본인확인, 개인 식별, 가입의사 확인, 연령확인</li>
                            <li><strong>서비스 제공:</strong> 레시피 추천, 가격 정보 제공, 맞춤형 서비스 제공</li>
                            <li><strong>서비스 개선:</strong> 서비스 이용 통계 분석, 신규 서비스 개발</li>
                            <li><strong>마케팅 및 광고:</strong> 이벤트 정보 및 참여기회 제공, 광고성 정보 제공</li>
                        </ul>
                    </div>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">2. 수집하는 개인정보의 항목</h2>
                    <div class="space-y-4 text-gray-700 dark:text-gray-300">
                        <div>
                            <h3 class="font-semibold text-lg mb-2">필수 항목</h3>
                            <ul class="list-disc list-inside ml-4 space-y-1">
                                <li>이메일 주소</li>
                                <li>이름</li>
                                <li>비밀번호 (암호화 저장)</li>
                            </ul>
                        </div>
                        <div>
                            <h3 class="font-semibold text-lg mb-2">선택 항목</h3>
                            <ul class="list-disc list-inside ml-4 space-y-1">
                                <li>프로필 사진</li>
                                <li>식품 알레르기 정보</li>
                                <li>식단 선호도</li>
                            </ul>
                        </div>
                        <div>
                            <h3 class="font-semibold text-lg mb-2">자동 수집 정보</h3>
                            <ul class="list-disc list-inside ml-4 space-y-1">
                                <li>서비스 이용 기록</li>
                                <li>접속 로그</li>
                                <li>쿠키</li>
                                <li>접속 IP 정보</li>
                            </ul>
                        </div>
                    </div>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">3. 개인정보의 보유 및 이용기간</h2>
                    <div class="space-y-3 text-gray-700 dark:text-gray-300">
                        <p>회사는 법령에 따른 개인정보 보유·이용기간 또는 정보주체로부터 개인정보를 수집 시에 동의받은 개인정보 보유·이용기간 내에서 개인정보를 처리·보유합니다.</p>
                        <ul class="list-disc list-inside ml-4 space-y-2">
                            <li><strong>회원 정보:</strong> 회원 탈퇴 시까지 (단, 관계 법령에 따라 보존할 필요가 있는 경우 해당 기간 동안 보관)</li>
                            <li><strong>서비스 이용기록:</strong> 3개월</li>
                            <li><strong>부정이용 기록:</strong> 1년</li>
                        </ul>
                        <p class="mt-4">관련 법령에 따른 보관:</p>
                        <ul class="list-disc list-inside ml-4 space-y-2">
                            <li>계약 또는 청약철회 등에 관한 기록: 5년</li>
                            <li>대금결제 및 재화 등의 공급에 관한 기록: 5년</li>
                            <li>소비자의 불만 또는 분쟁처리에 관한 기록: 3년</li>
                            <li>웹사이트 방문기록: 3개월</li>
                        </ul>
                    </div>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">4. 개인정보의 제3자 제공</h2>
                    <div class="space-y-3 text-gray-700 dark:text-gray-300">
                        <p>회사는 원칙적으로 이용자의 개인정보를 제3자에게 제공하지 않습니다. 다만, 다음의 경우에는 예외로 합니다:</p>
                        <ul class="list-disc list-inside ml-4 space-y-2">
                            <li>이용자가 사전에 동의한 경우</li>
                            <li>법령의 규정에 의거하거나, 수사 목적으로 법령에 정해진 절차와 방법에 따라 수사기관의 요구가 있는 경우</li>
                        </ul>
                    </div>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">5. 개인정보의 처리 위탁</h2>
                    <div class="space-y-3 text-gray-700 dark:text-gray-300">
                        <p>회사는 원활한 서비스 제공을 위해 다음과 같이 개인정보 처리업무를 외부 전문업체에 위탁하여 처리할 수 있습니다:</p>
                        <div class="overflow-x-auto mt-4">
                            <table class="min-w-full border border-gray-300 dark:border-zinc-600">
                                <thead class="bg-gray-100 dark:bg-zinc-700">
                                    <tr>
                                        <th class="border border-gray-300 dark:border-zinc-600 px-4 py-2 text-left">수탁업체</th>
                                        <th class="border border-gray-300 dark:border-zinc-600 px-4 py-2 text-left">위탁업무 내용</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="border border-gray-300 dark:border-zinc-600 px-4 py-2">AWS</td>
                                        <td class="border border-gray-300 dark:border-zinc-600 px-4 py-2">클라우드 서버 호스팅</td>
                                    </tr>
                                    <tr>
                                        <td class="border border-gray-300 dark:border-zinc-600 px-4 py-2">Google Analytics</td>
                                        <td class="border border-gray-300 dark:border-zinc-600 px-4 py-2">서비스 이용 분석</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <p class="mt-4">회사는 위탁계약 체결 시 개인정보 보호법 제26조에 따라 위탁업무 수행목적 외 개인정보 처리금지, 기술적·관리적 보호조치, 재위탁 제한, 수탁자에 대한 관리·감독, 손해배상 등 책임에 관한 사항을 계약서 등 문서에 명시하고, 수탁자가 개인정보를 안전하게 처리하는지를 감독하고 있습니다.</p>
                    </div>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">6. 정보주체의 권리·의무 및 행사방법</h2>
                    <div class="space-y-3 text-gray-700 dark:text-gray-300">
                        <p>이용자는 언제든지 다음 각 호의 개인정보보호 관련 권리를 행사할 수 있습니다:</p>
                        <ul class="list-disc list-inside ml-4 space-y-2">
                            <li>개인정보 열람 요구</li>
                            <li>오류 등이 있을 경우 정정 요구</li>
                            <li>삭제 요구</li>
                            <li>처리정지 요구</li>
                        </ul>
                        <p class="mt-4">권리 행사는 서비스 내 설정 메뉴를 통하거나, 개인정보 보호책임자에게 서면, 전화, 전자우편 등을 통하여 하실 수 있으며 회사는 이에 대해 지체없이 조치하겠습니다.</p>
                    </div>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">7. 개인정보의 파기</h2>
                    <div class="space-y-3 text-gray-700 dark:text-gray-300">
                        <p>회사는 개인정보 보유기간의 경과, 처리목적 달성 등 개인정보가 불필요하게 되었을 때에는 지체없이 해당 개인정보를 파기합니다.</p>
                        <div class="mt-4">
                            <h3 class="font-semibold text-lg mb-2">파기 절차</h3>
                            <p>이용자가 입력한 정보는 목적 달성 후 별도의 DB에 옮겨져(종이의 경우 별도의 서류) 내부 방침 및 기타 관련 법령에 따라 일정기간 저장된 후 혹은 즉시 파기됩니다.</p>
                        </div>
                        <div class="mt-4">
                            <h3 class="font-semibold text-lg mb-2">파기 방법</h3>
                            <ul class="list-disc list-inside ml-4 space-y-1">
                                <li>전자적 파일 형태: 기록을 재생할 수 없도록 영구 삭제</li>
                                <li>종이에 출력된 개인정보: 분쇄기로 분쇄하거나 소각</li>
                            </ul>
                        </div>
                    </div>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">8. 개인정보 보호책임자</h2>
                    <div class="space-y-3 text-gray-700 dark:text-gray-300">
                        <p>회사는 개인정보 처리에 관한 업무를 총괄해서 책임지고, 개인정보 처리와 관련한 정보주체의 불만처리 및 피해구제 등을 위하여 아래와 같이 개인정보 보호책임자를 지정하고 있습니다.</p>
                        <div class="bg-gray-50 dark:bg-zinc-700/50 rounded-lg p-6 mt-4">
                            <p><strong>개인정보 보호책임자</strong></p>
                            <ul class="mt-2 space-y-1">
                                <li>회사명: (주)루펜드</li>
                                <li>담당부서: 운영팀</li>
                                <li>이메일: contact@loofend.com</li>
                            </ul>
                        </div>
                    </div>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">9. 개인정보 처리방침의 변경</h2>
                    <div class="space-y-3 text-gray-700 dark:text-gray-300">
                        <p>본 개인정보처리방침은 시행일로부터 적용되며, 법령 및 방침에 따른 변경내용의 추가, 삭제 및 정정이 있는 경우에는 변경사항의 시행 7일 전부터 공지사항을 통하여 고지할 것입니다.</p>
                    </div>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">10. 쿠키의 운용</h2>
                    <div class="space-y-3 text-gray-700 dark:text-gray-300">
                        <p>회사는 이용자에게 개별적인 맞춤서비스를 제공하기 위해 이용정보를 저장하고 수시로 불러오는 '쿠키(cookie)'를 사용합니다.</p>
                        <div class="mt-4">
                            <h3 class="font-semibold text-lg mb-2">쿠키의 사용 목적</h3>
                            <ul class="list-disc list-inside ml-4 space-y-1">
                                <li>이용자의 접속 빈도나 방문 시간 등을 분석</li>
                                <li>이용자의 관심분야 파악 및 자취 추적</li>
                                <li>각종 이벤트 참여 정도 및 방문 회수 파악 등을 통한 타겟 마케팅</li>
                            </ul>
                        </div>
                        <p class="mt-4">이용자는 쿠키 설치에 대한 선택권을 가지고 있으며, 웹브라우저에서 옵션을 설정함으로써 모든 쿠키를 허용하거나, 쿠키가 저장될 때마다 확인을 거치거나, 모든 쿠키의 저장을 거부할 수도 있습니다.</p>
                    </div>
                </section>

                <section class="mt-12 pt-8 border-t border-gray-200 dark:border-zinc-700">
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">부칙</h2>
                    <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                        본 방침은 2025년 10월 25일부터 시행합니다.
                    </p>
                </section>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-200 dark:border-zinc-700">
                <a href="{{ route('home') }}" wire:navigate class="inline-flex items-center text-orange-600 dark:text-orange-400 hover:text-orange-700 dark:hover:text-orange-300 font-medium">
                    <flux:icon.arrow-left class="w-4 h-4 mr-2" />
                    홈으로 돌아가기
                </a>
            </div>
        </div>
    </div>
</div>
