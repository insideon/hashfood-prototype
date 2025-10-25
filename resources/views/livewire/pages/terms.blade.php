<?php

use function Livewire\Volt\layout;

layout('components.layouts.guest');

?>

<div class="min-h-screen bg-gray-50 dark:bg-zinc-900 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-zinc-800 rounded-2xl shadow-xl border border-gray-200 dark:border-zinc-700 p-8">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">이용약관</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">최종 수정일: 2025년 10월 25일</p>
            </div>

            <div class="prose prose-gray dark:prose-invert max-w-none space-y-8">
                <section>
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">제1조 (목적)</h2>
                    <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                        본 약관은 (주)루펜드(이하 "회사")가 운영하는 해시푸드 서비스(이하 "서비스")의 이용과 관련하여 회사와 회원 간의 권리, 의무 및 책임사항, 기타 필요한 사항을 규정함을 목적으로 합니다.
                    </p>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">제2조 (용어의 정의)</h2>
                    <div class="space-y-3 text-gray-700 dark:text-gray-300">
                        <p><strong>1. "회사"</strong>란 (주)루펜드를 의미합니다.</p>
                        <p><strong>2. "서비스"</strong>란 회원이 이용할 수 있는 해시푸드의 모든 서비스를 의미합니다.</p>
                        <p><strong>3. "회원"</strong>이란 본 약관에 따라 이용계약을 체결하고, 서비스를 이용하는 자를 말합니다.</p>
                        <p><strong>4. "레시피"</strong>란 서비스에서 제공하는 요리법 및 관련 정보를 의미합니다.</p>
                        <p><strong>5. "가격 정보"</strong>란 식자재의 시장 가격 정보를 의미합니다.</p>
                    </div>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">제3조 (약관의 효력 및 변경)</h2>
                    <div class="space-y-3 text-gray-700 dark:text-gray-300">
                        <p>1. 본 약관은 서비스 화면에 게시하거나 기타의 방법으로 회원에게 공지함으로써 효력이 발생합니다.</p>
                        <p>2. 회사는 필요한 경우 관련 법령을 위배하지 않는 범위에서 본 약관을 변경할 수 있습니다.</p>
                        <p>3. 약관이 변경되는 경우 회사는 변경사항을 시행일자 7일 전부터 공지합니다.</p>
                    </div>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">제4조 (서비스의 제공 및 변경)</h2>
                    <div class="space-y-3 text-gray-700 dark:text-gray-300">
                        <p>1. 회사가 제공하는 서비스는 다음과 같습니다:</p>
                        <ul class="list-disc list-inside ml-4 space-y-2">
                            <li>레시피 정보 제공</li>
                            <li>식자재 가격 정보 제공</li>
                            <li>요리 비용 분석 및 비교</li>
                            <li>가격 트래킹 서비스</li>
                            <li>AI 기반 레시피 추천</li>
                        </ul>
                        <p>2. 회사는 서비스의 내용을 변경할 경우 그 내용 및 제공일자를 명시하여 공지합니다.</p>
                    </div>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">제5조 (회원가입)</h2>
                    <div class="space-y-3 text-gray-700 dark:text-gray-300">
                        <p>1. 회원가입은 이용자가 약관의 내용에 동의하고, 회사가 정한 가입 양식에 따라 정보를 기입함으로써 이루어집니다.</p>
                        <p>2. 회사는 다음 각 호에 해당하는 경우 회원가입을 거부할 수 있습니다:</p>
                        <ul class="list-disc list-inside ml-4 space-y-2">
                            <li>타인의 명의를 이용한 경우</li>
                            <li>허위 정보를 기재한 경우</li>
                            <li>사회의 안녕질서 또는 미풍양속을 저해할 목적으로 신청한 경우</li>
                        </ul>
                    </div>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">제6조 (회원정보의 변경)</h2>
                    <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                        회원은 개인정보 관리화면을 통하여 언제든지 본인의 개인정보를 열람하고 수정할 수 있습니다. 회원은 회원가입 시 기재한 사항이 변경되었을 경우 즉시 수정해야 하며, 수정하지 않음으로 인해 발생하는 문제의 책임은 회원에게 있습니다.
                    </p>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">제7조 (회원의 의무)</h2>
                    <div class="space-y-3 text-gray-700 dark:text-gray-300">
                        <p>회원은 다음 행위를 해서는 안 됩니다:</p>
                        <ul class="list-disc list-inside ml-4 space-y-2">
                            <li>허위 내용의 등록</li>
                            <li>타인의 정보 도용</li>
                            <li>서비스에 게시된 정보의 무단 변경</li>
                            <li>회사가 정한 정보 이외의 정보 등의 송신 또는 게시</li>
                            <li>회사와 기타 제3자의 저작권 등 지적재산권에 대한 침해</li>
                            <li>기타 불법적이거나 부당한 행위</li>
                        </ul>
                    </div>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">제8조 (서비스 이용의 제한 및 중지)</h2>
                    <div class="space-y-3 text-gray-700 dark:text-gray-300">
                        <p>1. 회사는 다음 각 호에 해당하는 경우 서비스 이용을 제한하거나 중지할 수 있습니다:</p>
                        <ul class="list-disc list-inside ml-4 space-y-2">
                            <li>서비스용 설비의 보수 등 공사로 인한 부득이한 경우</li>
                            <li>회원이 회사의 서비스 운영을 고의 또는 과실로 방해하는 경우</li>
                            <li>기타 천재지변, 국가비상사태 등 불가항력적 사유가 있는 경우</li>
                        </ul>
                    </div>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">제9조 (면책조항)</h2>
                    <div class="space-y-3 text-gray-700 dark:text-gray-300">
                        <p>1. 회사는 천재지변 또는 이에 준하는 불가항력으로 인하여 서비스를 제공할 수 없는 경우 책임이 면제됩니다.</p>
                        <p>2. 회사는 회원의 귀책사유로 인한 서비스 이용의 장애에 대하여 책임을 지지 않습니다.</p>
                        <p>3. 회사는 서비스에서 제공하는 가격 정보의 정확성을 보장하지 않으며, 해당 정보는 참고용으로만 사용되어야 합니다.</p>
                        <p>4. 회사는 회원이 서비스를 이용하여 기대하는 수익을 얻지 못하거나 상실한 것에 대하여 책임을 지지 않습니다.</p>
                    </div>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">제10조 (분쟁의 해결)</h2>
                    <div class="space-y-3 text-gray-700 dark:text-gray-300">
                        <p>1. 본 약관에 명시되지 않은 사항은 관련 법령의 규정과 상관례에 따릅니다.</p>
                        <p>2. 서비스 이용으로 발생한 분쟁에 대해 소송이 제기되는 경우 회사의 본사 소재지를 관할하는 법원을 관할 법원으로 합니다.</p>
                    </div>
                </section>

                <section class="mt-12 pt-8 border-t border-gray-200 dark:border-zinc-700">
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">부칙</h2>
                    <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                        본 약관은 2025년 10월 25일부터 시행합니다.
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
