<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class Pages extends BaseModel
{
    protected $table = 'pages';

    protected static function boot()
    {
        parent::boot();


        static::addGlobalScope('english', function (Builder $builder) {
            $builder->where('language_id', 1);
        });
    }

    public static function insertData($language)
    {
        $title = 'Terms of Service';
        $check = \App\Models\Pages::where('title', $title)->where('language_id', $language->id)->first();
        if (!$check) {
            $page = new \App\Models\Pages();
            $page->title = $title;
            $page->slug = str_slug(strtolower($title));
            $page->language_id = $language->id;
            $page->description = ' THIS IS AN AGREEMENT BETWEEN YOU OR THE ENTITY THAT YOU REPRESENT (hereinafter "You" or "Your") AND HRM SAAS (hereinafter "HRM SAAS") GOVERNING YOUR USE OF TIME TRACKER SYSTEM – HRM (hereinafter the "Software").

<h4>1. Acceptance of Terms</h4>

This Agreement consists of the following terms and conditions referred to as the "Terms". You must be of legal age to enter into a binding agreement in order to accept the Terms. If you do not agree to the Terms, do not use the Software. You can accept the Terms by checking a checkbox or clicking on a button indicating your acceptance of the terms or by actually using the Software.

<h4>2. Description of Software</h4>

{{ $setting->main_name }}, an HR Management solution. You may use the Software for your personal and business use or for internal business purpose in the organization that you represent. You may connect to the Software using any Internet browser supported by the Software. You are responsible for obtaining access to the Internet and the equipment necessary to use the Software. You can create and edit content with your user account and if you choose to do so, you can publish and share such content.

<h4>3. Modification of Terms of Software</h4>

We may modify the Terms upon notice to you at any time through a service announcement or by sending email to your primary email address. If we make significant changes to the Terms that affect your rights, you will be provided with at least 30 days advance notice of the changes by email to your primary email address. You may terminate your use of the Software by providing HRM SAAS notice by email within 30 days of being notified of the availability of the modified Terms if the Terms are modified in a manner that substantially affects your rights in connection with use of the Software. In the event of such termination, you will be entitled to prorated refund of the unused portion of any prepaid fees. Your continued use of the Software after the effective date of any change to the Terms will be deemed to be your agreement to the modified Terms.

<h4>4. User Sign up Obligations</h4>

You need to sign up for a user account by providing all required information in order to access or use the Software. If you represent an organization and wish to use the Software for corporate internal use, we recommend that you, and all other users from your organization, sign up for user accounts by providing your corporate contact information. In particular, we recommend that you use your corporate email address. You agree to: a) provide true, accurate, current and complete information about yourself as prompted by the sign-up process; and b) maintain and promptly update the information provided during sign up to keep it true, accurate, current, and complete. If you provide any information that is untrue, inaccurate, outdated, or incomplete, or if HRM SAAS has reasonable grounds to suspect that such information is untrue, inaccurate, outdated, or incomplete, HRM SAAS may terminate your user account and refuse current or future use of the Software.

<h4>5. Organization Accounts and Administrators</h4>

When you sign up for an account for your organization you may specify one or more administrators. The administrators will have the right to configure the Software based on your requirements and manage end users in your organization account. If your organization account is created and configured on your behalf by a third party, it is likely that such third party has assumed administrator role for your organization. Make sure that you enter into a suitable agreement with such third party specifying such party’s roles and restrictions as an administrator of your organization account. You are responsible for:
<ol>
<li>ensuring confidentiality of your organization account password,</li>
<li>appointing competent individuals as administrators for managing your organization account, and</li>
<li>ensuring that all activities that occur in connection with your organization account comply with this Agreement. You understand that HRM SAAS is not responsible for account administration and internal management of the Software for you.</li>
</ol>
You are responsible for taking necessary steps for ensuring that your organization does not lose control of the administrator accounts. You may specify a process to be followed for recovering control in the event of such loss of control of the administrator accounts by sending an email to <a href="mailto:admin@example.com">admin@example.com</a>, provided that the process is acceptable to HRM SAAS. In the absence of any specified administrator account recovery process, HRM SAAS may provide control of an administrator account to an individual providing proof satisfactory to HRM SAAS demonstrating authorization to act on behalf of the organization. You agree not to hold HRM SAAS liable for the consequences of any action taken by HRM SAAS in good faith in this regard.

<h4>6. Self-Hosted Terms</h4>

<h5>6.1 Your License Rights</h5>

Subject to the terms and conditions of this Agreement, HRM SAAS grants you a non-exclusive, non-sublicensable and non-transferable license to install and use the Software during the applicable License Term in accordance with this Agreement.

<h5>6.2 Number of Instances</h5>

Unless otherwise specified in your Order, for each Software license that you purchase, you may install one instance of the Software on systems owned or operated by you (or your third party service providers so long as you remain responsible for their compliance with the terms and conditions of this Agreement).

<h5>6.3 Your Modifications</h5>

Subject to the terms and conditions of this Agreement:

<ol>
<li>for any elements of the Software provided by HRM SAAS in source code form, and to the extent permitted in the Documentation, you may modify such source code solely for purposes of developing bug fixes, customizations and additional features for the Software and</li>

<li>you may also modify the Documentation to reflect your permitted modifications of the Software source code or the particular use of the Products within your organization.</li>
</ol>

Any modified source code or Documentation constitutes "Your Modifications". You may use Your Modifications solely with respect to your own instances in support of your permitted use of the Software but you may not distribute the code of Your Modifications to any third party. Notwithstanding anything in this Agreement to the contrary, HRM SAAS has no support, warranty, indemnification or other obligation or liability with respect to Your Modifications or their combination, interaction or use with our Products. You shall indemnify, defend and hold us harmless from and against any and all claims, costs, damages, losses, liabilities and expenses (including reasonable attorneys’ fees and costs) arising out of or in connection with any claim brought against us by a third party relating to Your Modifications (including but not limited to any representations or warranties you make about Your Modifications or the Software) or your breach of this Section 6.3. This indemnification obligation is subject to your receiving (i) prompt written notice of such claim (but in any event notice in sufficient time for you to respond without prejudice); (ii) the exclusive right to control and direct the investigation, defense, or settlement of such claim; and (iii) all reasonably necessary cooperation of HRM SAAS at your expense.

<h5>6.4 Attribution</h5>

In any use of the Software, you must include the following attribution to HRM SAAS on all user interfaces in the following format: "Powered by HRM SAAS" which must in every case include a hyperlink to http://www.solutionrack.com, and which must be in the same format as delivered in the Software. If no such attribution is delivered with the Software, it is not required to include such attribution.

<h5>6.5 Support</h5>

The support provided by HRM SAAS for self-hosted services is limited to the issues arising because of bugs in the Software delivered. It does not include (i) issues arising as a result of outdated software running on server on which you choose to install the Software (ii) issues arising as a result

of Your Modifications as described in section 6.5 (iii) issues arising because server not meeting System Requirements for the Software.

<h4>7. Personal Information and Privacy</h4>

Personal information you provide to HRM SAAS through the Software is governed by TIME TRACKER SYSTEM – HRM Privacy Policy. Your election to use the Software indicates your acceptance of the terms of the TIME TRACKER SYSTEM – HRM Privacy Policy. You are responsible for maintaining confidentiality of your username, password and other sensitive information. You are responsible for all activities that occur in your user account and you agree to inform us immediately of any unauthorized use of your user account by email to <a href="mailto:admin@example.com">admin@example.com</a>. We are not responsible for any loss or damage to you or to any third party incurred as a result of any unauthorized access and/or use of your user account, or otherwise.

<h4>8. Communications from HRM SAAS</h4>

The Software may include certain communications from HRM SAAS, such as service announcements, administrative messages and newsletters. You understand that these communications shall be considered part of using the Software. As part of our policy to provide you total privacy, we also provide you the option of opting out from receiving newsletters from us. However, you will not be able to opt-out from receiving service announcements and administrative messages.

<h4>9. Complaints</h4>

If we receive a complaint from any person against you with respect to your activities as part of use of the Software, we will forward the complaint to the primary email address of your user account. You must respond to the complainant directly within 10 days of receiving the complaint forwarded by us and copy HRM SAAS in the communication. If you do not respond to the complainant within 10 days from the date of our email to you, we may disclose your name and contact information to the complainant for enabling the complainant to take legal action against you. You understand that your failure to respond to the forwarded complaint within the 10 days’ time limit will be construed as your consent to disclosure of your name and contact information by HRM SAAS to the complainant.

<h4>10. Fees and Payments</h4>

The Software is available under monthly subscription plan. Your subscription can be automatically renewed at the end of each month unless you downgrade your paid subscription plan to a free plan or inform us that you do not wish to renew the subscription. If you do not wish to renew the subscription, you must inform us at least seven days prior to the renewal date. If you have not downgraded to a free plan and if you have not informed us that you do not wish to renew the subscription, you will be presumed to have authorized HRM SAAS to charge the subscription fee. From time to time, we may change the price of any Software or charge for use of Services that are currently available free of charge. Any increase in charges will not apply until the expiry of your then current billing cycle. You will not be charged for using any Software unless you have opted for a paid subscription plan.

<h4>11. Restrictions on Use</h4>

In addition to all other terms and conditions of this Agreement, you shall not: (i) transfer the Software or otherwise make it available to any third party; (ii) provide any service based on the Software without prior written permission; (iii) use the third party links to sites without agreeing to their website terms & conditions; (iv) post links to third party sites or use their logo, company name, etc. without their prior written permission; (v) publish any personal or confidential information belonging to any person or entity without obtaining consent from such person or entity; (vi) use the Software in any manner that could damage, disable, overburden, impair or harm any server, network, computer system, resource of HRM SAAS; (vii) violate any applicable local, state, national or international law; and (viii) create a false identity to mislead any person as to the identity or origin of any communication.

<h4>12. Spamming and Illegal Activities</h4>

You agree to be solely responsible for the contents of your transmissions through the Software. You agree not to use the Software for illegal purposes or for the transmission of material that is unlawful, defamatory, harassing, libelous, invasive of another\'s privacy, abusive, threatening, harmful, vulgar, pornographic, obscene, or is otherwise objectionable, offends religious sentiments, promotes racism, contains viruses or malicious code, or that which infringes or may infringe intellectual property or other rights of another. You agree not to use the Software for the transmission of "junk mail", "spam", "chain letters", "phishing" or unsolicited mass distribution of email. We reserve the right to terminate your access to the Software if there are reasonable grounds to believe that you have used the Software for any illegal or unauthorized activity.

<h4>13. Inactive User Accounts Policy</h4>

            We reserve the right to terminate unpaid user accounts that are inactive for a continuous period of 120 days. In the event of such termination, all data associated with such user account will be deleted. We will provide you prior notice of such termination and option to back-up your data. In case of accounts with more than one user, if at least one of the users is active, the account will not be considered inactive.

<h4>14. Data Ownership</h4>

            We respect your right to ownership of content created or stored by you. You own the content created or stored by you. Unless specifically permitted by you, your use of the Software does not grant HRM SAAS the license to use, reproduce, adapt, modify, publish or distribute the content created by you or stored in your user account for HRM SAAS\'s commercial, marketing or any similar purpose. But you grant HRM SAAS permission to access, copy, distribute, store, transmit, reformat, publicly display and publicly perform the content of your user account solely as required for the purpose of providing the Software to you.

<h4>15. User Generated Content</h4>

You may transmit or publish content created by you using the Software or otherwise. However, you shall be solely responsible for such content and the consequences of its transmission or publication. Any content made public will be publicly accessible through the internet and may be crawled and indexed by search engines. You are responsible for ensuring that you do not accidentally make any private content publicly available. Any content that you may receive from other users of the Software, is provided to you AS IS for your information and personal use only and you agree not to use, copy, reproduce, distribute, transmit, broadcast, display, sell, license or otherwise exploit such content for any purpose, without the express written consent of the person who owns the rights to such content. In the course of using the Software, if you come across any content with copyright notice(s) or any copy protection feature(s), you agree not to remove such copyright notice(s) or disable such copy protection feature(s) as the case may be. By making any copyrighted/copyrightable content available on the Software you affirm that you have the consent, authorization or permission, as the case may be from every person who may claim any rights in such content to make such content available in such manner. Further, by making any content available in the manner aforementioned, you expressly agree that HRM SAAS will have the right to block access to or remove such content made available by you if HRM SAAS receives complaints

concerning any illegality or infringement of third party rights in such content. By using the Software and transmitting or publishing any content using such Software, you expressly consent to determination of questions of illegality or infringement of third party rights in such content by the agent designated by HRM SAAS for this purpose.

<h4>16. Disclaimer of Warranties</h4>

YOU EXPRESSLY UNDERSTAND AND AGREE THAT THE USE OF THE SERVICE IS AT YOUR SOLE RISK. THE SERVICES ARE PROVIDED ON AN AS-IS-AND-AS-AVAILABLE BASIS. HRM SAAS EXPRESSLY DISCLAIMS ALL WARRANTIES OF ANY KIND, WHETHER EXPRESS OR IMPLIED, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE. HRM SAAS MAKES NO WARRANTY THAT THE SERVICES WILL BE UNINTERRUPTED, TIMELY, SECURE, OR ERROR FREE. USE OF ANY MATERIAL DOWNLOADED OR OBTAINED THROUGH THE USE OF THE SERVICES SHALL BE AT YOUR OWN DISCRETION AND RISK AND YOU WILL BE SOLELY RESPONSIBLE FOR ANY DAMAGE TO YOUR COMPUTER SYSTEM, MOBILE TELEPHONE, WIRELESS DEVICE OR DATA THAT RESULTS FROM THE USE OF THE SERVICE OR THE DOWNLOAD OF ANY SUCH MATERIAL. NO ADVICE OR INFORMATION, WHETHER WRITTEN OR ORAL, OBTAINED BY YOU FROM HRM SAAS, ITS EMPLOYEES OR REPRESENTATIVES SHALL CREATE ANY WARRANTY NOT EXPRESSLY STATED IN THE TERMS.

<h4>17. Limitation of Liability</h4>

YOU AGREE THAT HRM SAAS SHALL, IN NO EVENT, BE LIABLE FOR ANY CONSEQUENTIAL, INCIDENTAL, INDIRECT, SPECIAL, PUNITIVE, OR OTHER LOSS OR DAMAGE WHATSOEVER OR FOR LOSS OF BUSINESS PROFITS, BUSINESS INTERRUPTION, COMPUTER FAILURE, LOSS OF BUSINESS INFORMATION, OR OTHER LOSS ARISING OUT OF OR CAUSED BY YOUR USE OF OR INABILITY TO USE THE SERVICE, EVEN IF HRM SAAS HAS BEEN ADVISED OF THE POSSIBILITY OF SUCH DAMAGE. IN NO EVENT SHALL HRM SAAS\'S ENTIRE LIABILITY TO YOU IN RESPECT OF THE SERVICE, WHETHER DIRECT OR INDIRECT, EXCEED THE FEES PAID BY YOU TOWARDS SUCH SERVICE.

<h4>18. Indemnification</h4>

            You agree to indemnify and hold harmless HRM SAAS, its officers, directors, employees, suppliers, and affiliates, from and against any losses, damages, fines and expenses (including attorney\'s fees and costs) arising out of or relating to any claims that you have used the Software in violation of another party\'s rights, in violation of any law, in violations of any provisions of the Terms, or any other claim related to your use of the Software, except where such use is authorized by HRM SAAS.

<h4>19. Suspension and Termination</h4>

            We may suspend your user account or temporarily disable access to whole or part of any Software in the event of any suspected illegal activity, extended periods of inactivity or requests by law enforcement or other government agencies. Objections to suspension or disabling of user accounts should be made to <a href="mailto:admin@example.com">admin@example.com</a> within thirty days of being notified about the suspension. We may terminate a suspended or disabled user account after thirty days. We will also terminate your user account on your request. In addition, we reserve the right to terminate your user account and deny the Software upon reasonable belief that you have violated the Terms. You have the right to terminate your user account if HRM SAAS breaches its obligations under these Terms and in such event, you will be entitled to prorated refund of

access to the Software, deletion of information in your user account such as your email address and password and deletion of all data in your user account.

<h4>20. END OF TERMS OF SERVICE</h4>

If you have any questions or concerns regarding this Agreement, please contact us at <a href="mailto:admin@example.com">admin@example.com</a>.';
            $page->save();

        }

        $title = 'Privacy Policy';
        $check = \App\Models\Pages::where('title', $title)->where('language_id', $language->id)->first();
        if (!$check) {
            $page = new \App\Models\Pages();
            $page->title = $title;
            $page->language_id = $language->id;
            $page->slug = str_slug(strtolower($title));
            $page->description = ' <h4>General</h4>

                        At HRM SAAS, we respect your need for online privacy and protect any Personal Information that you may share with us, in an appropriate manner. Our practice with respect to use of your Personal Information is as set forth below in this Privacy Policy Statement. As a condition to use of HRM SAAS Services, you consent to the terms of the Privacy Policy Statement as it may be updated from time to time.

                        <h4>Children\'s Online Privacy Protection</h4>

                        HRM SAAS does not knowingly collect Personal Information from users who are under 13 years of age.

                        <h4>Information Recorded and Use:</h4>

                        <h5>Personal Information</h5>

            During the Registration Process for creating a user account, we request for your name, email address and company name. You will also be asked to choose a password, which will be used solely for the purpose of providing access to your user account. Your name and email address will be used to inform you regarding new services, releases, upcoming events and changes in this Privacy Policy Statement.
                        <br><br/>
                        HRM SAAS will have access to third party personal information provided by you as part of using HRM SAAS Services. This information may include third party names, email addresses, phone numbers and physical addresses and will be used for servicing your requirements as expressed by you to HRM SAAS and solely as part and parcel of your use of HRM SAAS Services. We do not share this third party personal information with anyone for promotional purposes, nor do we utilize it for any purposes not expressly consented to by you. When you elect to refer friends to the website, we request their email address and name to facilitate the request and deliver a one time email. Your friend may contact us at <a href="mailto:admin@example.com">admin@example.com</a> to request that we remove this information from our database.
                        <br/><br/>
                        We post user testimonials on our website. These testimonials may include names and other Personal Information and we acquire permission from our users prior to posting these on our website.
                        <br/><br/>
                        HRM SAAS is not responsible for the Personal Information users elect to post within their testimonials.

                        <h5>Usage Details</h5>

            Your usage details such as time, frequency, duration and pattern of use, features used and the amount of storage used will be recorded by us in order to enhance your experience of the HRM SAAS Services and to help us provide you the best possible service.

                        <h5>Contents of your User Account</h5>

            We store and maintain files, documents, emails and other data stored in your user account at servers provided by cloud services like Microsoft Azure. In order to prevent loss of data due to errors or system failures, we also keep backup copies of data including the contents of your user account. Hence your files and data may remain on our servers even after deletion or termination of your user account. We may retain and use your Personal Information and data as necessary to comply with our legal obligations, resolve disputes, and enforce our rights. We assure you that the contents of your user account will not be disclosed to anyone and will not be accessible even to employees of HRM SAAS except in circumstances specifically mentioned in this Privacy Policy Statement and Terms of Services. We also do not scan the contents of your user account for serving targeted advertisements.

                        <h5>Payment Information</h5>

            In case of services requiring payment, we request credit card or other payment account information, which will be used solely for processing payments. Your financial information will not be stored by us except for the name and address of the card holder, the expiry date and the last four digits of the Credit Card number. Subject to your prior consent and where necessary for processing future payments, your financial information will be stored in encrypted form on secure servers of our reputed Payment Gateway Service Provider who is beholden to treating your Personal Information in accordance with this Privacy Policy Statement.


                        <h5>Behavioral Targeting/Re-Targeting</h5>

                        We partner with third parties to manage our advertisements on other sites. Our third-party partners may use technologies such as cookies to gather information about your activities on this site and other sites in order to provide you advertising based upon your browsing activities and interests. If you wish to not have this information used for the purpose of serving you interest-based advertisements, you may opt-out by <a href="http://preferences-mgr.truste.com/" target="_blank">clicking here</a> (or if located in the European Union <a href="http://www.youronlinechoices.eu/" _target="blank">click here</a>). However, you will continue to receive generic advertisements on other websites that display advertisements.

                        <h5>Links from our website</h5>

                        Some pages of our website contain external links. You are advised to verify the privacy practices of such other websites. We are not responsible for the manner of use or misuse of information made available by you at such other websites. We encourage you not to provide Personal Information, without assuring yourselves of the Privacy Policy Statement of other websites.

                        <h5>Federated Authentication</h5>

                        You can log in to our site, if option available, using federated authentication providers such as Facebook Connect. These services will authenticate your identity and provide you the option to share certain Personal Information with us such as your name and email address to pre-populate our sign up form.

                        <h5>With whom we share Information</h5>

                        We may need to share your Personal Information and your data to our affiliates, resellers, service providers and business partners solely for the purpose of providing HRM SAAS Services to you. The purposes for which we may disclose your Personal Information or data to our service providers may include, but are not limited to, data storage, database management, web analytics and payment processing. These service providers are authorized to use your Personal Information or data only as necessary to provide these services to us. In such cases HRM SAAS will also ensure that such affiliates, resellers, service providers and business partners comply with this Privacy Policy Statement and adopt appropriate confidentiality and security measures. We will obtain your prior specific consent before we share your Personal Information or data to any person outside HRM SAAS for any purpose that is not directly connected with providing HRM SAAS Services to you. We will share your Personal Information with third parties only in the ways that are described in this Privacy Policy Statement. We do not sell your Personal Information to third parties. We may share generic aggregated demographic information not linked to any Personal Information regarding visitors and users with our business partners and advertisers. Please be aware that laws in various jurisdictions in which we operate may obligate us to disclose user information and the contents of your user account to the local law enforcement authorities under a legal process or an enforceable government request. In addition, we may also disclose Personal Information and contents of your user account to law enforcement authorities if such disclosure is determined to be necessary by HRM SAAS in our sole and absolute discretion for protecting the safety of our users, employees, or the general public. If HRM SAAS is involved in a merger, acquisition, or sale of all or a portion of its business or assets, you will be notified via email and/or a prominent notice on our website of any change in ownership or uses of your Personal Information, as well as any choices you may have regarding your Personal Information.

                        <h5>How secure is your Information</h5>

                        We adopt industry appropriate data collection, storage and processing practices and security measures, as well as physical security measures to protect against unauthorized access, alteration, disclosure or destruction of your Personal Information, username, password, transaction information and data stored in your user account. Access to your name and email address is restricted to our employees who need to know such information in connection with providing HRM SAAS Services to you and are bound by confidentiality obligations.

                        <h5>Your Choice in Information Use</h5>

                        In the event we decide to use your Personal Information for any purpose other than as stated in this Privacy Policy Statement, we will offer you an effective way to opt out of the use of your Personal Information for those other purposes. From time to time, we may send emails to you regarding new services, releases and upcoming events. You may opt out of receiving newsletters and other secondary messages from HRM SAAS by selecting the ‘unsubscribe’ function present in every email we send. However, you will continue to receive essential transactional emails.
                        <br/><br/>
                        Accessing, Updating and Removing Personal Information Users who wish to correct, update or remove any Personal Information including those from a public forum, directory or testimonial on our site may do so either by accessing their user account or by contacting HRM SAAS Customer Support Services at <a href="mailto:admin@example.com">admin@example.com</a>. Such changes may take up to 48 hours to take effect. We respond to all enquiries within 30 days. Investigation of Illegal Activity We may need to provide access to your Personal Information and the contents of your user account to our employees and service providers for the purpose of investigating any suspected illegal activity or potential violation of the terms and conditions for use of HRM SAAS Services. However, HRM SAAS will ensure that such access is in compliance with this Privacy Policy Statement and subject to appropriate confidentiality and security measures.

                        <h5>Enforcement of Privacy Policy</h5>

                        We make every effort, including periodic reviews to ensure that Personal Information provided by you is used in conformity with this Privacy Policy Statement. If you have any concerns regarding our adherence to this Privacy Policy Statement or the manner in which Personal Information is used for the purpose of providing HRM SAAS Services, kindly contact HRM SAAS Customer Support Services at <a href="mailto:admin@example.com">admin@example.com</a>. We will contact you to address your concerns and we will also co-operate with regulatory authorities in this regard if needed.

                        <h5>Notification of Changes</h5>

                        We may modify the Privacy Policy Statement upon notice to you at any time through a service announcement or by sending email to your primary email address. If we make significant changes in the Privacy Policy Statement that affect your rights, You will be provided with at least 30 days advance notice of the changes by email to your primary email address. You may terminate your use of HRM SAAS Services by providing HRM SAAS notice by email within 30 days of being notified of the availability of the modified Privacy Policy Statement if the Privacy Policy Statement is modified in a manner that substantially affects your rights in connection with use of HRM SAAS Services. Your continued use of HRM SAAS Services after the effective date of any change to the Privacy Policy Statement will be deemed to be your agreement to the modified Privacy Policy Statement. You will not receive email notification of minor changes to the Privacy Policy Statement. If you are concerned about how your Personal Information is used, you should check back at our privacy policy periodically.

                        <h5>Blogs and Forums</h5>

                        We provide the capacity for users to post information in blogs and forums for sharing information in a public space on the website. This information is publicly available to all users of these forums and visitors to our website. We require registration to publish information, but given the public nature of both platforms, any Personal Information disclosed within these forums may be used to contact users with unsolicited messages. We encourage users to be cautious in disclosure of Personal Information in public forums as HRM SAAS is not responsible for the Personal Information users elect to disclose.
                        <br/><br/>
                        HRM SAAS also supports third party widgets such as Facebook and Twitter buttons on the website that allow users to share articles and other information on different platforms. These widgets may collect your IP address, which page you are visiting on our site, and may set a cookie to enable the widgets to function properly. These widgets do not collect or store any Personal Information from users on the website and simply act as a bridge for your convenience in sharing information. Your interactions with these widgets are governed by the privacy policy of the company providing it.

                        <h5>END OF PRIVACY POLICY</h5>

                        If you have any questions or concerns regarding this Privacy Policy Statement, please contact us at <a href="mailto:admin@example.com">admin@example.com</a>. We shall respond to all inquiries within 30 days of receipt upon ascertaining your identity.';
            $page->save();
        }


        $title = 'About Us';
        $check = \App\Models\Pages::where('title', $title)->where('language_id', $language->id)->first();
        if (!$check) {
            $page = new \App\Models\Pages();
            $page->title = $title;
            $page->language_id = $language->id;
            $page->slug = str_slug(strtolower($title));
            $page->description = '<div class="col-sm-12"><h2 class="main-heading sea-green">Overview</h2><div class="page-content"><p>Froiden is an outsourcing services provider for small and medium businesses, based in <a href="https://en.wikipedia.org/wiki/Jaipur" target="_blank" rel="noopener noreferrer">Jaipur, Rajasthan</a>. It is a young company incorporated in the year 2014.</p><p>We exclusively focus on providing services related to web applications. We do have plans for expanding to other emerging domains in near future.</p><p>We are not limited to providing outsourcing services. We have equal focus on developing our own products. While our products also are web applications and supporting mobile applications, we do not focus on any particular subject or industry. Our products tend to provide solutions to common problems faced by people. We do not develop products if similar ones are already in the market, unless the existing products can be greatly improved upon.</p><br></div><h2 class="main-heading sea-green">Difference Matters</h2><div class="page-content"><p>While working with Froiden, one thing that you will experience the most is the difference from others in the way we work and see things, and why is this difference so important.</p><p>Even smallest of things done differently can result in exceptional outcomes. This is what drives us to do things the way we think is right, and not just going by the book. This leads to innovations you find at Froiden.</p></div><h2 class="main-heading sea-green">More About Froiden</h2><div class="page-content"><p>Here are some links to learn more about us:</p><ul><li><a href="/about/history">History</a>: The story of Froiden - how it started and what lies ahead</li></ul></div><h2 class="main-heading sea-green">The Road Ahead</h2><div class="page-content"><p>At the time this site was published, Froiden was about to complete three years. It had lived the hardest period of a startup.</p><p>Our journey of three years has made us stronger and braver. Our team has grown to 14 members and together have achieved many milestones in terms of revenue.</p><p>Our goal for next few years is to take our products to new heights. To support that, we will keep growing our services business. We believe in <em>small team - big impact</em>, so we will expand our team to a limit.</p><p>Lastly, and obviously, we will keep working on new technologies and ideas, build new products, focus on innovation and build Froiden as a brand.</p></div></div>';
            $page->save();
        }
    }
}
