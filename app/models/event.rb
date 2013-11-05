class Event < ActiveRecord::Base
include Paperclip::Glue
attr_accessible :start_at, :end_at, :name ,:content,:venue,:address,:furl,:turl,:latitude,:longitude,:avatar,:city,:event_type_id,
:event_language_id,:start_hour,:start_minute,:start_meridiem,:end_hour,:end_minute,:end_meridiem,:state,:country,:zipcode
  has_attached_file :avatar, :styles => { :medium => "300x300>", :thumb => "267x175!>", :small => "146x97!>" }, :default_url => "default_upload_logo.gif"
  

  has_event_calendar
  has_one :event_type
  belongs_to :event_type
  belongs_to :event_language
  belongs_to :user
  has_many :user_signed
  has_many :uploads
  geocoded_by :address




   

end
