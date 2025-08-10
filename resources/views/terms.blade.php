@extends('layouts.app')

@section('title', '利用規約')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">TasteRetreat 利用規約</h1>
        
        <div class="bg-white shadow-lg rounded-lg p-6 space-y-6">
            <div class="text-sm text-gray-500 mb-6">
                <p>最終更新日: 2025年8月10日</p>
            </div>

            <section>
                <h2 class="text-xl font-semibold text-gray-800 mb-4">第1条（適用）</h2>
                <p class="text-gray-700 leading-relaxed">
                    本規約は、TasteRetreat（以下「当サービス」）の利用条件を定めるものです。
                    登録ユーザーの皆さま（以下「ユーザー」）には、本規約に従って当サービスをご利用いただきます。
                </p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-800 mb-4">第2条（利用登録）</h2>
                <ol class="list-decimal list-inside space-y-2 text-gray-700">
                    <li>登録希望者が当社の定める方法によって利用登録を申請し、当社がこれを承認することによって、利用登録が完了するものとします。</li>
                    <li>当社は、以下の場合には、利用登録の申請を承認しないことがあります。
                        <ul class="list-disc list-inside ml-6 mt-2 space-y-1">
                            <li>虚偽の事項を届け出た場合</li>
                            <li>本規約に違反したことがある者からの申請である場合</li>
                            <li>その他、当社が利用登録を相当でないと判断した場合</li>
                        </ul>
                    </li>
                </ol>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-800 mb-4">第3条（ユーザーIDおよびパスワードの管理）</h2>
                <ol class="list-decimal list-inside space-y-2 text-gray-700">
                    <li>ユーザーは、自己の責任において、当サービスのユーザーIDおよびパスワードを適切に管理するものとします。</li>
                    <li>ユーザーは、いかなる場合にも、ユーザーIDおよびパスワードを第三者に譲渡または貸与することはできません。</li>
                    <li>当社は、ユーザーIDとパスワードの組み合わせが登録情報と一致してログインされた場合には、そのユーザーIDを登録しているユーザー自身による利用とみなします。</li>
                </ol>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-800 mb-4">第4条（投稿コンテンツ）</h2>
                <ol class="list-decimal list-inside space-y-2 text-gray-700">
                    <li>ユーザーは、当サービスに投稿する写真、テキスト、その他のコンテンツについて、自らが投稿することについての適法な権利を有していること、および投稿コンテンツが第三者の権利を侵害していないことを保証するものとします。</li>
                    <li>ユーザーは、投稿コンテンツについて、当社に対し、世界的、非独占的、無償、サブライセンス可能かつ譲渡可能な使用、複製、配布、派生著作物の作成、表示および実行に関するライセンスを付与します。</li>
                    <li>ユーザーは、当社および当社から権利を承継しまたは許諾された者に対して著作者人格権を行使しないことに同意するものとします。</li>
                </ol>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-800 mb-4">第5条（禁止事項）</h2>
                <p class="text-gray-700 mb-2">ユーザーは、当サービスの利用にあたり、以下の行為をしてはなりません。</p>
                <ul class="list-disc list-inside space-y-1 text-gray-700">
                    <li>法令または公序良俗に違反する行為</li>
                    <li>犯罪行為に関連する行為</li>
                    <li>当社、他のユーザー、その他第三者の知的財産権、肖像権、プライバシー、名誉その他の権利または利益を侵害する行為</li>
                    <li>当サービスの運営を妨害するおそれのある行為</li>
                    <li>虚偽の情報を投稿する行為</li>
                    <li>不正アクセスをし、またはこれを試みる行為</li>
                    <li>他のユーザーに関する個人情報等を収集または蓄積する行為</li>
                    <li>他のユーザーに成りすます行為</li>
                    <li>当社のサービスに関連して、反社会的勢力に対して直接または間接に利益を供与する行為</li>
                    <li>その他、当社が不適切と判断する行為</li>
                </ul>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-800 mb-4">第6条（当サービスの提供の停止等）</h2>
                <p class="text-gray-700 mb-2">
                    当社は、以下のいずれかの事由があると判断した場合、ユーザーに事前に通知することなく当サービスの全部または一部の提供を停止または中断することができるものとします。
                </p>
                <ul class="list-disc list-inside mt-2 space-y-1 text-gray-700">
                    <li>当サービスにかかるコンピュータシステムの保守点検または更新を行う場合</li>
                    <li>地震、落雷、火災、停電または天災などの不可抗力により、当サービスの提供が困難となった場合</li>
                    <li>コンピュータまたは通信回線等が事故により停止した場合</li>
                    <li>その他、当社が当サービスの提供が困難と判断した場合</li>
                </ul>
                <p class="text-gray-700 mt-4">
                    当社は、当サービスの提供の停止または中断により、ユーザーまたは第三者が被ったいかなる不利益または損害についても、一切の責任を負わないものとします。
                </p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-800 mb-4">第7条（利用制限および登録抹消）</h2>
                <ol class="list-decimal list-inside space-y-2 text-gray-700">
                    <li>当社は、ユーザーが以下のいずれかに該当する場合には、事前の通知なく、ユーザーに対して、当サービスの全部もしくは一部の利用を制限し、またはユーザーとしての登録を抹消することができるものとします。
                        <ul class="list-disc list-inside ml-6 mt-2 space-y-1">
                            <li>本規約のいずれかの条項に違反した場合</li>
                            <li>登録事項に虚偽の事実があることが判明した場合</li>
                            <li>料金等の支払債務の不履行があった場合</li>
                            <li>当社からの連絡に対し、一定期間返答がない場合</li>
                            <li>当サービスについて、最終の利用から一定期間利用がない場合</li>
                            <li>その他、当社が当サービスの利用を適当でないと判断した場合</li>
                        </ul>
                    </li>
                    <li>当社は、本条に基づき当社が行った行為によりユーザーに生じた損害について、一切の責任を負いません。</li>
                </ol>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-800 mb-4">第8条（退会）</h2>
                <p class="text-gray-700 leading-relaxed">
                    ユーザーは、当社の定める退会手続により、当サービスから退会できるものとします。
                </p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-800 mb-4">第9条（保証の否認および免責事項）</h2>
                <ol class="list-decimal list-inside space-y-2 text-gray-700">
                    <li>当社は、当サービスに事実上または法律上の瑕疵（安全性、信頼性、正確性、完全性、有効性、特定の目的への適合性、セキュリティなどに関する欠陥、エラーやバグ、権利侵害などを含みます。）がないことを明示的にも黙示的にも保証しておりません。</li>
                    <li>当社は、当サービスに起因してユーザーに生じたあらゆる損害について、当社の故意又は重過失による場合を除き、一切の責任を負いません。</li>
                    <li>前項ただし書に定める場合であっても、当社は、当社の過失（重過失を除きます。）による債務不履行または不法行為によりユーザーに生じた損害のうち特別な事情から生じた損害（当社またはユーザーが損害発生につき予見し、または予見し得た場合を含みます。）について一切の責任を負いません。</li>
                    <li>当社は、当サービスに関して、ユーザーと他のユーザーまたは第三者との間において生じた取引、連絡または紛争等について一切責任を負いません。</li>
                </ol>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-800 mb-4">第10条（サービス内容の変更等）</h2>
                <p class="text-gray-700 leading-relaxed">
                    当社は、ユーザーへの事前の通知なく、当サービスの内容を変更、追加または廃止することがあり、ユーザーはこれを承諾するものとします。
                </p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-800 mb-4">第11条（利用規約の変更）</h2>
                <ol class="list-decimal list-inside space-y-2 text-gray-700">
                    <li>当社は以下の場合には、ユーザーの個別の同意を要せず、本規約を変更することができるものとします。
                        <ul class="list-disc list-inside ml-6 mt-2 space-y-1">
                            <li>本規約の変更がユーザーの一般の利益に適合するとき</li>
                            <li>本規約の変更が本規約の目的に反せず、かつ、変更の必要性、変更後の内容の相当性その他の変更に係る事情に照らして合理的なものであるとき</li>
                        </ul>
                    </li>
                    <li>当社はユーザーに対し、前項による本規約の変更にあたり、事前に、本規約を変更する旨および変更後の本規約の内容並びにその効力発生時期を通知します。</li>
                </ol>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-800 mb-4">第12条（個人情報の取扱い）</h2>
                <p class="text-gray-700 leading-relaxed">
                    当社は、当サービスの利用によって取得する個人情報については、当社「プライバシーポリシー」に従い適切に取り扱うものとします。
                </p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-800 mb-4">第13条（通知または連絡）</h2>
                <p class="text-gray-700 leading-relaxed">
                    ユーザーと当社との間の通知または連絡は、当社の定める方法によって行うものとします。当社は、ユーザーから、当社が別途定める方式に従った変更届け出がない限り、現在登録されている連絡先が有効なものとみなして当該連絡先へ通知または連絡を行い、これらは、発信時にユーザーへ到達したものとみなします。
                </p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-800 mb-4">第14条（権利義務の譲渡の禁止）</h2>
                <p class="text-gray-700 leading-relaxed">
                    ユーザーは、当社の書面による事前の承諾なく、利用契約上の地位または本規約に基づく権利もしくは義務を第三者に譲渡し、または担保に供することはできません。
                </p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-800 mb-4">第15条（準拠法・裁判管轄）</h2>
                <ol class="list-decimal list-inside space-y-2 text-gray-700">
                    <li>本規約の解釈にあたっては、日本法を準拠法とします。</li>
                    <li>当サービスに関して紛争が生じた場合には、当社の本店所在地を管轄する裁判所を専属的合意管轄とします。</li>
                </ol>
            </section>

            <div class="text-sm text-gray-500 mt-8 pt-6 border-t border-gray-200">
                <p>制定日: 2025年1月7日</p>
                <p>最終更新日: 2025年1月7日</p>
            </div>
        </div>
    </div>
</div>
@endsection